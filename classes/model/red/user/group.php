<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Default ACL user.
 * 
 * @package		Red
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://www.gnu.org/licenses/gpl-3.0
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