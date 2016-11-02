# Usage

The following code snppets expect that you have extended all models with the following scheme:

    class Model_User extends Model_Red_User {}

The code snippets are also based on basic knowledge of Kohana's ORM module.

## Role Creation

Groups can be inserted manually via SQL Query or also using PHP:

    $role = ORM::factory('User_Role');
    
    $role->values(array(
        'name' => 'user', // Name should be lowercase without whitespace.
        'description' => 'Normal user group',
    ));
    
    $role->save();

## User Creation

For user creation:

    $user = ORM::factory('User');
    
    // Set values:
    $user->values(array(
        'first_name' => 'David',
        'last_name' => 'Stutz',
        'email' => 'davidstutz@web.de'
        'password' => '8JFs$df238d(§H3)', // Take a secure password!
    ));
    
    // Assign the 'user' group to the new user.
    $user->add('roles', ORM::factory('User_Role', array('name' => 'user')));
    
    $user->save();

If you are using user salts:

    $user = ORM::factory('User');
    // The salt need to be added before the password is added!
    $user->sale = $salt;
    $user->values(array(
        // When adding salt here it will not work:
        // 'salt' => $salt,
        'email' => 'davidstutz@web.de',
        'password' => '8JFs$df238d(§H3)', // Take a secure password!
    ));
    $user->save();

## User Login

The form:

    <?php echo Form::open(); ?>
        <?php echo Form::label('email', __('Email')); ?>
        <?php echo Form::input('email', NULL); ?>
        <?php echo Form::label('password', __('Password')); ?>
        <?php echo Form::password('password', NULL); ?>
        <?php echo Form::checkbox('remember', 'remember'); ?>
        <?php echo Form::label('remember', __('Remember login')); ?>
        <?php echo Form::submit('sumbit', __('Submit')); ?>
    <?php echo Form::close(); ?> 

In the controller:

    // Check whether the user is already logged in or get auto login working.
    if (Red::instance()->logged_in()) {
        $this->redirect(...);
    }
    
    if (Request::POST === $this->request->method()) {
        // Get the remember option:
        $remember = Arr::get($this->request->post(), 'remember', FALSE);
        
        // Login the user with email and password:
        if (Red::instance()->login($this->request->post('email'), $this->request->post('password'), $remember)) {
            //Login successful.
            $this->redirect(...);
        }
        else {
            // Show error message ...
        }
    }
    
Login is currently only possible with the user's email.
    
## Check User is Logged In

To check whether a user is logged in:

    if (Red::instance()->logged_in()) {
        // User is logged in ...
    }
    
## User Logout

To logout the currently logged in user:

    // Logout the current logged in user.
    Red::instance()->logout();

Additionally the current session can be destroyed by setting a flag:

    // Additionally destroy the session.
    Red::instance()->logout(TRUE);
    
## Singleton and Useful Methods

To have access to the currently logged in user the Red class is implemented as singleton and provides the following methods:

    // Get the current user. Will return the ORM model or FALSE if no user is logged in.
    $user = Red::instance()->get_user();
    
    if ($user) {
        echo $user->id;
    }
    
    // Check whether a user is logged in:
    if (Red::instance()->logged_in()) {
        // User is logged in.
        // get_user() will return the user model:
        $user = Red::instance()->get_user();
    }
    
    // Hash some passwords or similar:
    $hashed = Red::hash($password, $user);
