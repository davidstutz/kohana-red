<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Default user token.
 * 
 * @package		Red
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://www.gnu.org/licenses/gpl-3.0
 */
class Model_Red_User_Token extends ORM
{

	/**
	 * @var	string	used table
	 */
	protected $_table = 'user_tokens';

	/**
	 * @var	array 	created column
	 */
	protected $_created_column = array(
		'column' => 'created',
		'format' => 'U',
	);

	/**
	 * @var	array 	belongs to
	 */
	protected $_belongs_to = array(
		'user' => array()
	);

	/**
	 * Handles garbage collection and deleting of expired objects.
	 */
	public function __construct($id = NULL)
	{
		parent::__construct($id);

		if (mt_rand(1, Kohana::$config->load('acl.gc')) === 1)
		{
			/* Delete expired tokens. */
			DB::delete($this->_table_name)
				->where('expires', '<', time())
				->execute($this->_db);
		}

		if ($this->expires < time() AND $this->_loaded)
		{
			$this->delete();
		}
	}

	/**
	 * Create token.
	 */
	protected function create_token()
	{
		do
		{
			$token = sha1(uniqid(Text::random('alnum', 32), TRUE));
		}
		while(ORM::factory('user_token', array('token' => $token))->loaded());

		return $token;
	}

}