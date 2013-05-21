<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Default ACL user.
 *
 * @package     Red
 * @author      David Stutz
 * @copyright   (c) 2013 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Model_Red_User_Role extends ORM {

    /**
     * @var string  used table
     */
    protected $_table = 'user_roles';
    
    /**
     * @var	array 	has many users
     */
    protected $_has_many = array(
        'users' => array(
            'model' => 'user',
            'through' => 'users_user_roles',
            'foreign_key' => 'user_role_id',
            'far_key' => 'user_id',
        ),
    );
}
