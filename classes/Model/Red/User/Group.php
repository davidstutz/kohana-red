<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Default ACL user.
 * 
 * @package		Red
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://opensource.org/licenses/bsd-3-clause
 */
class Model_Red_User_Group extends ORM {

	/**
	 * @var	array 	has many users
	 */
	protected $_has_many = array(
		'users' => array(
			'model' => 'user',
			'foreign_key' => 'group_id',
		),
	);
}