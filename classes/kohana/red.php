<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Red - Auth implementation.
 *
 * @package     Red
 * @author      David Stutz
 * @copyright   (c) 2013 - 2016 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Kohana_Red {

    /**
     * @var    object    instance
     */
    protected static $_instance = FALSE;

    /**
     * Singleton.
     *
     * @return    object    Red
     */
    public static function instance() {
        if (Red::$_instance === FALSE OR !is_object(Red::$_instance)) {
            Red::$_instance = new Red();
        }

        return Red::$_instance;
    }

    /**
     * @var    object    logger
     */
    protected $_logger;

    /**
     * @var    object    session instance
     */
    protected $_session;

    /**
     * @var    array     config
     */
    protected $_config;

    /**
     * @var    object    user
     */
    protected $_user = FALSE;

    /**
     * Loads Session and configuration options.
     */
    private function __construct() {
        $config = Kohana::$config->load('red');
        
        /**
         * First check for hash key.
         * If no hash key is given or its FALSE throw an exception.
         * Then Red is not properly configured.
         */
        if (!isset($config['password']['key']) OR empty($config['password']['key'])) {
            throw new Red_Exception('A valid hash key must be set in your Red config.');
        }
        
        /**
         * Now check for login hash method and key.
         */
        if (!isset($config['login']['key']) OR empty($config['login']['key'])) {
            throw new Red_Exception('A valid token key must be set in your Red config.');
        }
        
        /**
         * And check hash key and method used for tokens.
         */
       if (!isset($config['token']['key']) OR empty($config['token']['key'])) {
            throw new Red_Exception('A valid token key must be set in your Red config.');
       }
       
        $this->_config = Kohana::$config->load('red');
        $this->_session = Session::instance($this->_config['session']['type']);
    }

    /**
     * Gets the currently logged in user from the session.
     * Returns NULL if no user is currently logged in.
     *
     * @return    mixed    current user or FALSE
     */
    public function get_user() {
        
        if ($this->_user === FALSE) {
            $id = $this->_session->get($this->_config['session']['key'], FALSE);
            
            /**
             * If no session is active check for a remembered user using auto_login.
             */
            if ($id === FALSE) {
                return $this->auto_login();
            }

            $this->_user = ORM::factory('user', $id);
        }

        return $this->_user->loaded() ? $this->_user : FALSE;
    }

    /**
     * Perform a hmac hash, using the configured method.
     *
     * @throws    Red_Exception
     * @param    string    string to hash
     * @return    string    hash
     */
    public static function hash($password, $user) {
        $config = Kohana::$config->load('red');
        
        /**
         * For hash strengthening iterations are done.
         * Using do-while so the password is as minimum hashed once.
         * The application and user salt is applied in each iteration.
         */
        $i = 0;
        do {
            $password = hash_hmac($config['password']['method'], $config['password']['salt'] . $password . (isset($user->salt) ? $user->salt : ''), $config['password']['key']);
            $i++;
        } while($i < (int)$config['password']['iterations']);

        return $password;
    }

    /**
     * Attempt to log in a user by using an ORM object and plain-text password.
     *
     * @param    string    email to log in
     * @param    string    password to check against
     * @param    boolean    enable autologin
     * @return    boolean
     */
    public function login($email, $password, $remember = FALSE) {
        /**
         * First check login delay.
         * Note: The login delay is not based on the email the user try to login with.
         */
        $login = ORM::factory('user_login')->where('ip', '=', hash_hmac($this->_config['login']['method'], Request::$client_ip, $this->_config['login']['key']))
            ->and_where('agent', '=', hash_hmac($this->_config['login']['method'], Request::$user_agent, $this->_config['login']['key']))
            ->and_where('created', '>', time() - $this->_config['login']['delay'])
            ->find();

        if ($login->loaded()) {
            return FALSE;
        }

        /**
         * Will register the login before evaluating.
         * Will save users ip, email and time of login.
         */
        $login = ORM::factory('user_login')->values(array(
            'ip' => hash_hmac($this->_config['login']['method'], Request::$client_ip, $this->_config['login']['key']),
            'agent' => hash_hmac($this->_config['login']['method'], Request::$user_agent, $this->_config['login']['key']),
            'login' => $email,
        ))->create();

        /**
         * Search user and check password.
         * If password empty, quit. Else search user and evaluate passwords.
         */
        if (empty($password)) {
            return FALSE;
        }

        $user = ORM::factory('user')->where('email', '=', $email)->find();

        if (!$user->loaded()) {
            return FALSE;
        }
        
        if ($user->password !== Red::hash($password, $user)) {
            return FALSE;
        }

        /**
         * Store the user id in the current session.
         * This will indicate whether the user is logged in or not.
         */
        $this->_session->regenerate();
        $this->_session->set($this->_config['session']['key'], $user->id);
        
        if (!$this->_session->write()) {
            Kohana::$log->add(Log::DEBUG, 'Could not login user due to session write failure - write returned FALSE.');
            return FALSE;
        }
        
        if ($this->_session->get($this->_config['session']['key'], FALSE) === FALSE) {
            Kohana::$log->add(Log::DEBUG, 'Could not login user due to session write failure - written value could not be retrieved.');
            return FALSE;
        }
        
        /**
         * Remember the user. Create token for this purpose.
         * Save the unique token in a cookie.
         */
        if ($remember !== FALSE) {
            $token = ORM::factory('user_token')->values(array(
                'user_id' => $user->id,
                'expires' => time() + $this->_config['token']['lifetime'],
                'user_agent' => hash_hmac($this->_config['token']['method'], Request::$user_agent, $this->_config['token']['key']),
            ));
            $token->create();

            Cookie::set($this->_config['token']['cookie_key'], $token->token, $this->_config['token']['lifetime']);
        }
        
        /**
         * Store a reference to the user with the current login.
         */
        $login->user = $user;
        $login->update();
        
        return $user;
    }

    /**
     * Log out a user by removing the related session variables.
     *
     * @param   boolean  completely destroy the session
     * @return  boolean
     */
    public function logout($destroy = FALSE) {
        if ($destroy === TRUE) {
            $this->_session->destroy();
        }
        else {
            $this->_session->delete($this->_config['session']['key']);
            $this->_session->regenerate();
        }
        
        /**
         * Do not forget to delete the remember me cookie and appropriate token
         * in the database. So the user is not logged in automatically again.
         */
        if ($token = Cookie::get($this->_config['token']['cookie_key'], FALSE)) {
            Cookie::delete($this->_config['token']['cookie_key']);

            $token = ORM::factory('user_token', array('token' => $token));

            if ($token->loaded()) {
                $token->delete();
            }
        }

        return !$this->logged_in();
    }

    /**
     * Logs a user in, based on the authautologin cookie.
     *
     * @return    mixed    false or user to be logged in
     */
    public function auto_login() {
        
        $token = Cookie::get($this->_config['token']['cookie_key'], FALSE);
        if (FALSE !== $token) {
            /**
             * The corresponding token will be laoded.
             * If the token is expired it will be deleted.
             */
            $token = ORM::factory('user_token', array('token' => $token));
            
            if ($token->loaded() AND $token->user->loaded()) {
                if ($token->user_agent == hash_hmac($this->_config['token']['method'], Request::$user_agent, $this->_config['token']['key'])) {
                    
                    Cookie::set($this->_config['token']['cookie_key'], $token->token, $token->expires - time());
                    
                    $this->_session->regenerate();
                    $this->_session->set($this->_config['session']['key'], $token->user->id);
                    $this->_session->write();
                    
                    $this->_user = $token->user;

                    return $this->_user;
                }
            }
        }

        return FALSE;
    }

    /**
     * Checks if a session is active (if a user is currenlty logged in).
     *
     * @return  boolean
     */
    public function logged_in() {
        $user = $this->get_user();
        
        if ($user === FALSE) {
            return FALSE;
        }

        return TRUE;
    }

}
