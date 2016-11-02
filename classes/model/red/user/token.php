<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Default user token.
 *
 * @package     Red
 * @author      David Stutz
 * @copyright   (c) 2013 - 2016 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
class Model_Red_User_Token extends ORM {

    /**
     * @var    string    used table
     */
    protected $_table = 'user_tokens';

    /**
     * @var array   created column
     */
    protected $_created_column = array(
        'column' => 'created',
        'format' => 'Y-m-d H:i:s', // MySQL TIMESTAMP format.
    );

    /**
     * @var    array     belongs to
     */
    protected $_belongs_to = array('user' => array());

    /**
     * Handles garbage collection and deleting of expired objects.
     *
     * @param int id
     */
    public function __construct($id = NULL) {
        parent::__construct($id);

        $config = Kohana::$config->load('red.token');
        if ((int)$config['gc'] < 1) {
            $config['gc'] = 100;
        }
        
        if (mt_rand(1, (int)$config['gc']) === 1) {
            DB::delete($this->_table_name)->where('expires', '<', time())->execute($this->_db);
        }

        if ($this->_loaded AND $this->expires < time()) {
            $this->delete();
        }
    }

    /**
     * Creates a new token by generating a unique token string.
     *
     * @param validation  validation
     */
    public function create(Validation $validation = NULL) {
        do {
            $token = sha1(uniqid(Text::random('alnum', 32), TRUE));
        } while (ORM::factory('user_token')->where('token', '=', $token)->count_all() > 0);

        $this->token = $token;

        return parent::create($validation);
    }

}
