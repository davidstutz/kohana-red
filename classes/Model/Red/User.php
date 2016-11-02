<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Default Red user.
 *
 * @package     Red
 * @author      David Stutz
 * @copyright   (c) 2013 - 2016 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Model_Red_User extends ORM {

    /**
     * @var string  used table
     */
    protected $_table = 'users';
    
    /**
     * @var array     has many tokens and logins
     */
    protected $_has_many = array(
        'tokens' => array(
            'model' => 'user_token',
            'foreign_key' => 'user_id',
        ),
        'logins' => array(
            'model' => 'user_login',
            'foreign_key' => 'user_id',
        ),
        'roles' => array(
            'model' => 'user_role',
            'through' => 'users_user_roles',
            'foreign_key' => 'user_id',
            'far_key' => 'user_role_id',
        ),
    );

    /**
     * Filters for password.
     *
     * @return    array     filters
     */
    public function filters() {
        return array(
            'password' => array(
                array('Red::hash', array(':value', $this)),
            ),
        );
    }

    /**
     * Check for unqiue username.
     *
     * @param    string    username
     * @return    boolean    unique
     */
    public static function unique_username($username) {
        return 0 == ORM::factory('User')->where('username', '=', $username)->count_all();
    }

    /**
     * Check for unqiue email.
     *
     * @param    string    email
     * @return    boolean    unique
     */
    public static function unique_email($email) {
        return 0 == ORM::factory('User')->where('email', '=', $email)->count_all();
    }

}
