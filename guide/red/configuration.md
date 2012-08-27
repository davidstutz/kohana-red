# Configuration

See the configuration file, following configuration options are given:

## Hash Configuration

	'hash'  => array(
		'method' => 'sha256',
		'key' => '',
		'iterations' => 1000,
	),
	
* method: Defines the hash method used. **md5 should not be used!** sha256 or sha512 are good choices.
* key: The key used for hmac. **Should be filled with a unique key!**
* iterations: The number of hash iterations. Multiple hash iterations will improve security. Thus to a good performance of hash method implementation and PHP high numbers can be used! (e.g. 1000 iterations)

## Lifetime

	'lifetime' => 1209600,
	
* lifetime: The lifetime of the session key after login.

## Session

	'session' => array(
		'type' => 'database',
		'key'  => 'red_user',
	),
	
* type: The type for the Kohana session driver. See the Kohana documentation for more information about session drivers.
* key: The session key.

## Autologin

	'autologin' => array(
		'key' => '',
	),
	
* key: The key for the autologin cookie.

## Login

	'login' => array(
		'method' => 'sha256', // Method for hashing IPs and user agents.
		'key' => '',
		'delay' => 10, // Delay between logins in seconds.
		'store' => 100, // Store the last x logins. FALSE to store all of them.
	),
	
* method: The hash method for hashing IPs and user agents. sha256 and sha512 are good choices, but for this scenario md5 or other hash functions can also be used, because this part is no security critical part.
* key: The key for hmac. **Should be filled with a unique key!**
* delay: The delay between logins. If the user tries to login multiple times within this delay time the login is denied and the timer started anew. Zero for no delay between logins.
* store: All logins are stored for the last x seconds.

## Garbace Collector

	'gc' => 100,
	
* gc: The chance that the garbace collector for the tokens will be run is 1/100.