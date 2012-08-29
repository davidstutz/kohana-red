<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Red - Auth implementation.
 * 
 * @package		Red
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://www.gnu.org/licenses/gpl-3.0
 */
class Kohana_Red
{

	/**
	 * @var	object	instance
	 */
	protected static $_instance = FALSE;

	/**
	 * Singleton.
	 *
	 * @return	object	Red
	 */
	public static function instance()
	{
		if (Red::$_instance === FALSE
			OR !is_object(Red::$_instance))
		{
			Red::$_instance = new Red();
		}

		return Red::$_instance;
	}
	 
	/**
	 * @var	object	logger
	 */
	protected $_logger;

	/**
	 * @var	object	session instance
	 */
	protected $_session;

	/**
	 * @var	array 	config
	 */
	protected $_config;

	/**
	 * @var	object	user
	 */
	protected $_user = FALSE;

	/**
	 * Loads Session and configuration options.
	 */
	public function __construct()
	{
		$this->_config = Kohana::$config->load('red');
		
		$this->_session = Session::instance($this->_config['session']['type']);
	}

	/**
	 * Gets the currently logged in user from the session.
	 * Returns NULL if no user is currently logged in.
	 *
	 * Usage:
	 * 	$user = Red::instance()->get_user();
	 * 
	 * 	if (!$user)
	 * 	{
	 * 		// ...
	 * 	}
	 * 
	 * @return	mixed	current user or FALSE
	 */
	public function get_user()
	{
		if ($this->_user === FALSE)
		{
			$id = $this->_session->get($this->_config['session']['key'], FALSE);
			
			if ($id === FALSE)
			{
				return FALSE;
			}
			
			$this->_user = ORM::factory('user', $id);
		}
		
		return $this->_user->loaded() ? $this->_user : FALSE;
	}

	/**	
	 * Perform a hmac hash, using the configured method.
	 *
	 * @throws	Red_Exception
	 * @param	string	string to hash
	 * @return	string	hash
	 */
	public static function hash($password, $user)
	{
		$config = Kohana::$config->load('red');
		
		/**
		 * First check for hash key.
		 * If no hash key is given or its FALSE throw an exception.
		 * Then Red is not properly configured.
		 */
		if (!isset($config['hash']['key'])
			OR empty($config['hash']['key']))
		{
			throw new Red_Exception('A valid hash key must be set in your Red config.');
		}

		/**
		 * Now check for login key.
		 */
		if (!isset($config['login']['key'])
			OR empty($config['login']['key']))
		{
			throw new Red_Exception('A valid login key must be set in your Red config.');
		}

		/**
		 * For hash strengthening iterations are done.
		 */
		$password = $config['salt']['application'] . $password . (isset($user->salt) ? $user->salt : '');
		$i = 0;
		do {
			$password = hash_hmac($config['hash']['method'], $password, $config['hash']['key']);
			$i++;
		} while($i < $config['hash']['iterations']);
		
		return $password;
	}

	/**
	 * Attempt to log in a user by using an ORM object and plain-text password.
	 *
	 * @param	string	email to log in
	 * @param	string	password to check against
	 * @param	boolean	enable autologin
	 * @return	boolean
	 * @uses	Cookie
	 */
	public function login($email, $password, $remember = FALSE)
	{
		/**
		 * First check login delay.
		 */
		$login = ORM::factory('user_login')
			->where('ip', '=', hash_hmac($this->_config['login']['method'], Request::$client_ip, $this->_config['login']['key']))
			->and_where('agent', '=', hash_hmac($this->_config['login']['method'], Request::$user_agent, $this->_config['login']['key']))
			->and_where('time', '>', date('Y-m-d H:i:s', time() - $this->_config['login']['delay']))
			->find();
		
		if ($login->loaded())
		{
			return FALSE;
		}
		
		/**
		 * Will register the login before evaluating.
		 * Will save users ip and time of login.
		 */
		ORM::factory('user_login')
			->values(array(
				'ip' => hash_hmac($this->_config['login']['method'], Request::$client_ip, $this->_config['login']['key']),
				'agent' => hash_hmac($this->_config['login']['method'], Request::$user_agent, $this->_config['login']['key']),
				'login' => $email,
			))->create();
		
		/**
		 * Search user and check password.
		 * If password empty, quit. Else search user and evaluate passwords.
		 */
		if (empty($password))
		{
			return FALSE;
		}
		
		$user = ORM::factory('user')->where('email', '=', $email)->find();
		
		if (!$user->loaded())
		{
			return FALSE;
		}
		
		if ($user->password !== Red::hash($password, $user))
		{
			return FALSE;
		}
		
		/**
		 * Remember the user. Create token for this purpose.
		 */
		if ($remember === TRUE)
		{
			$token = ORM::factory('user_token')
				->values(array(
					'user_id'    => $user->id,
					'expires'    => time() + $this->_config['lifetime'],
					'user_agent' => hash_hmac($this->_config['hash']['method'], Request::$user_agent, $this->_config['hash']['key']),
				))
				->create();

			Cookie::set($this->_config['autologin']['key'], $token->token, $this->_config['lifetime']);
		}

		$this->_session->regenerate();

		$this->_session->set($this->_config['session']['key'], $user->id);
		
		return $user;
	}

	/**
	 * Log out a user by removing the related session variables.
	 *
	 * @param   boolean  completely destroy the session
	 * @return  boolean
	 */
	public function logout($destroy = FALSE)
	{
		if ($destroy === TRUE)
		{
			$this->_session->destroy();
		}
		else
		{
			$this->_session->delete($this->_config['session']['key']);
			
			$this->_session->regenerate();
		}

		if ($token = Cookie::get($this->_config['autologin']['key'], FALSE))
		{
			Cookie::delete($this->_config['autologin']['key']);

			$token = ORM::factory('user_token', array('token' => $token));

			if ($token->loaded())
			{
				$token->delete();
			}
		}

		return !$this->logged_in();
	}
	
	/**
	 * Logs a user in, based on the authautologin cookie.
	 *
	 * @return	mixed	false or user to be logged in
	 */
	public function auto_login()
	{
		if ($token = Cookie::get($this->_config['autologin']['key']))
		{
			$token = ORM::factory('user_token', array('token' => $token));

			if ($token->loaded()
				AND $token->user->loaded())
			{
				if ($token->user_agent === sha1(Request::$user_agent))
				{
					$token->save();

					Cookie::set($this->_config['autologin']['key'], $token->token, $token->expires - time());

					return $this->complete_login($token->user);
				}

				$token->delete();
			}
		}

		return FALSE;
	}

	/**
	 * Checks if a session is active.
	 *
	 * Usage:
	 * 	if (!Red::instance()->logged_in())
	 * 	{
	 * 		// Redirect...
	 * 	}
	 * 
	 * @return  boolean
	 */
	public function logged_in()
	{
		$user = $this->get_user();

		if ($user === FALSE)
		{
			return FALSE;
		}

		return TRUE;
	}
}
