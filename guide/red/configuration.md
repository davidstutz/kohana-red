# Configuration

**Also see the documentation on proper [Session Configuration](session-configuration).**

The configuration file looks as follows:

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

Password configuration options:

* `method`: Defines the hash method used. `sha256` or `sha512` are good choices. **`md5` and `sha1` should not be used!**
* `key`: The key used for HMAC. **Should be filled with a unique (random) key!**
* `iterations`: The number of hash iterations to hash the passwords - multiple hash iterations will improve security. Several hundred or thousand iterations are recommended!
* `salt`: A salt used application wide. Note that this salt is combined with a user-specific salt.

To set user specific salts, follow the below example:

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

Configuration options regarding logins:
    
* `method`: Hash method used for hashing IPs and user agents.
* `key`: The key used for HMAC. **Should be filled with a unique (random) key!**
* `delay`: The delay between login attempts.
* `store`: Store logins for the given number of seconds.

Session configuration options:
    
* `type`: The type for the Kohana session driver. See the Kohana documentation for more information about session drivers or [Session Configuration](session-configuration) for examples.
* `key`: The session key.

Tokens are used to remember logged in users. Token configuration options:
    
* `method`: The hash method for hashing IPs and user agents.
* `key`: The key for HMAC. **Should be filled with a unique key!**
* `lifetime`: The lifetime for the tokens and cookies. So the lifetime determines how long the auto login is working after the last login.
* `cookie_key`: The used key for the cookies.
* `gc`: The chance that the garbage collector will be run is set as `1/gc`.