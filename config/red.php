<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Red configuration.
 * 
 * @package     Red
 * @author      David Stutz
 * @copyright   (c) 2013 - 2014 David Stutz
 * @license     http://opensource.org/licenses/bsd-3-clause
 */
return array(

	/**
	 * Password hashing configurations.
	 * 
	 * The method defines used hash algorithms. 
	 * Do not use md5 or sha1, these are not considered secure any more.
	 * Use one of the sha2 family instead.
	 * The key for the hmac.
	 * The nubmer of iterations. Thus to a good speed of most hash algorithms ~1000
	 * iterations are not any problem and considered secure.
	 */
	'password'  => array(
		'method' => 'sha256',
		'key' => '',
		'iterations' => 10000,
		/**
         * Salt configuration.
         * 
         * The application wide salt (configure here) is added to each password.
         * It should have at least 20 random characters.
         * A user salt can be added manually in the 'salt' column of
         * the suer table and should contain around 20 random characters.
         */
		'salt' => '', 
	),
	
	/**
	 * Session configuration: Session type to use and session key used.
	 */
	'session' => array(
		'type' => 'database',
		'key'  => 'red_user',
	),
	
	/**
	 * Options concerning the logins.
	 */
	'login' => array(
		'method' => 'sha256', // Method for hashing IPs and user agents.
		'key' => '',
		'delay' => 10, // Delay between logins in seconds.
		'store' => 604800, // Store logins for x seconds.
	),
	
    /**
     * Tokens are used to remember a user looged in.
     * This is done using cookies. A token is saved in the database and a reference
     * to this token is set in a cookie with the key cookie_key.
     */
    'token' => array(
        'method' => 'sha256',
        'key' => '',
        'lifetime' => 1209600, // Lifetime of the cookie and the token.
        'cookie_key' => '', // The key for the cookie to use.
        'gc' => 100, // Garbage collector is run in 1/100 of times.
    ),
);
