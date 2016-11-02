# Session Configuration

The following example is taken from the [Kohana Session Documentation](https://kohanaframework.org/3.3/guide/kohana/sessions).

**Note that either the `native` or the `database` driver should be used with Red!**

    return array(
        'native' => array(
            // Session name, use a unique (maybe random) name!
            'name' => 'session_name',
            // Lifetime in seconds.
            'lifetime' => 43200,
        ),
        'cookie' => array(
            // Name of the used cookie, use a unique (maybe random) name!
            'name' => 'cookie_name',
            // Whether to encrypt the session.
            'encrypted' => TRUE,
            // Lifetime in seconds.
            'lifetime' => 43200,
        ),
        'database' => array(
            // Session name, use a unique (maybe random) name!
            'name' => 'cookie_name',
            // Whether to encrypt the session.
            'encrypted' => TRUE,
            // Lifetime in seconds.
            'lifetime' => 43200,
            'group' => 'default',
            'table' => 'table_name',
            'columns' => array(
                'session_id'  => 'session_id',
                'last_active' => 'last_active',
                'contents'    => 'contents'
            ),
            'gc' => 500,
        ),
    );

Note that using the `database` adapter/driver is recommended due to security. The
corresponding SQL schema is given below:

    CREATE TABLE  `sessions` (
        `session_id` VARCHAR(24) NOT NULL,
        `last_active` INT UNSIGNED NOT NULL,
        `contents` TEXT NOT NULL,
        PRIMARY KEY (`session_id`),
        INDEX (`last_active`)
    ) ENGINE = MYISAM;