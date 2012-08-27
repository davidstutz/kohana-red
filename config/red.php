<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Red configuration.
 * 
 * @package		Red
 * @author		David Stutz
 * @copyright	(c) 2012 David Stutz
 * @license		http://www.gnu.org/licenses/gpl-3.0
 */
return array(

	/**
	 * Hash configuration.
	 * 
	 * The method defines used hash algorithms. 
	 * Do not use md5 or sha1, these are not considered secure any more.
	 * Use one of the sha2 family instead.
	 * The key for the hmac.
	 * The nubmer of iterations. Thus to a good speed of most hash algorithms ~1000
	 * iterations are not any problem and considered secure.
	 */
	'hash'  => array(
		'method' => 'sha256',
		'key' => '',
		'iterations' => 1000,
	),
	
	/**
	 * Lifetime. 
	 */
	'lifetime' => 1209600,
	
	/**
	 * Session configuration.
	 */
	'session' => array(
		'type' => 'database',
		'key'  => 'red_user',
	),
	
	/**
	 * Key used for autologin.
	 */
	'autologin' => array(
		'key' => '',
	),
	
	/**
	 * Options concerning the login.
	 */
	'login' => array(
		'method' => 'sha256', // Method for hashing IPs and user agents.
		'key' => '',
		'delay' => 10, // Delay between logins in seconds.
		'store' => 604800, // Store logins for x seconds.
	),
	
	/**
	 * Garbace collector for tokens.
	 */
	'gc' => 100,
);
