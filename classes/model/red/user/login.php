<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Login model for login registration.
 * 
 * @package		Red
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://opensource.org/licenses/bsd-3-clause
 */
class Model_Red_User_Login extends ORM {

	/**
	 * @var	string	used table
	 */
	protected $_table = 'user_logins';

	/**
	 * Belongs to a user.
	 */
	protected $_belongs_to = array(
		'user' => array(
			'foreign_key' => 'username',
			'model' => 'user',
		),
	);
	
	/**
	 * Delete all non associated logins except the last one.
	 */
	public function __construct($id = NULL)
	{
		parent::__construct($id);

		$config = Kohana::$config->load('red.login');
		DB::delete($this->_table_name)
			->where('time', '<', time() - $config['store'])
			->execute($this->_db);
	}
}