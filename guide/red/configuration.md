# Configuration

See [Models](models) for table and model configuration.

See the configuration file, following configuration options are given:

## Password

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
	
* method: Defines the hash method used. **md5 and sha1 should not be used!** sha256 or sha512 are good choices.
* key: The key used for hmac. **Should be filled with a unique key!**
* iterations: The number of hash iterations. Multiple hash iterations will improve security. Thus to a good performance of hash method implementation and PHP high numbers can be used! (e.g. 1000 iterations)
* salt: An application wide salt.

User salts can be added manually. The user salt is saved in the 'salt' column of the user table. If this column does not exist, user salts will not be used. To use user salts you have to manually add a salt to each user with his creation.

Note the following if you are going to use user salts:

	$user = ORM::factory('user');
	// The salt need to be added before the password is added!
	$user->salt = $salt;
	$user->values(array(
		// When adding salt here it will not work:
		// 'salt' => $salt,
		'email' => 'davidstutz@web.de',
		'password' => '8JFs$df238d(§H3)', // Take a secure password!
	));
	$user->save();

## Salt

	/**
	 * Options concerning the logins.
	 */
	'login' => array(
		'method' => 'sha256', // Method for hashing IPs and user agents.
		'key' => '',
		'delay' => 10, // Delay between logins in seconds.
		'store' => 604800, // Store logins for x seconds.
	),
	
* method: Hash method used for hashing IPs and user agents.
* key: The key used for hmac. **Should be filled with a unique key!**
* delay: The delay between login attempts.
* store: Store logins for the given number of seconds.

## Session

	/**
	 * Session configuration: Session type to use and session key used.
	 */
	'session' => array(
		'type' => 'database',
		'key'  => 'red_user',
	),
	
* type: The type for the Kohana session driver. See the Kohana documentation for more information about session drivers.
* key: The session key.

## Login

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
	
* method: The hash method for hashing IPs and user agents.
* key: The key for hmac. **Should be filled with a unique key!**
* lifetime: The lifetime for the tokens and cookies. So the lifetime determines how long the auto login is working after the last login.
* cookie_key: The used key for the cookies.
* gc: The chance that the garbace collector for the tokens will be run is 1/100.