<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Default Red user.
 * 
 * @package		Red
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://www.gnu.org/licenses/gpl-3.0
 */
class Model_Red_User extends ORM
{

	/**
	 * @var array 	has many tokens and logins
	 */
	protected $_has_many = array(
		'tokens' => array(
			'model' => 'user_token',
			'foreign_key' => 'user_id',
		),
		'logins' => array(
			'model' => 'user_login'
		),
	);
	
	/**
	 * @var array 	belongs to group
	 */
	protected $_belongs_to = array(
		'group' => array(
			'model' => 'user_group',
			'foreign_key' => 'group_id',
		),
	);
	
	/**
	 * Filters for password.
	 * 
	 * @return	array 	filters
	 */
	public function filters()
	{
		return array(
			'password' => array(
				array('Red::hash', array(':value', $this))
			),
		);
	}
	
	/**
	 * Check for unqiue username.
	 * 
	 * @param	string	username
	 * @return	boolean	unique
	 */
	public static function unique_username($username)
	{
		return 0 == ORM::factory('user')->where('username', '=', $username)->count_all();
	}
	
	/**
	 * Check for unqiue email.
	 * 
	 * @param	string	email
	 * @return	boolean	unique
	 */
	public static function unique_email($email)
	{
		return 0 == ORM::factory('user')->where('email', '=', $email)->count_all();
	}
}