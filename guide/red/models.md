# Models

The following models are provided by the module.

* user
* user_group
* user_login: Will save all the logins of the last x seconds.
* user_token: Tokens are used to remember the login of a user.

All models should be extended using the following scheme:

	class Model_User extends Model_Red_User
	
By extending the models additional relationhsips and methods can be added. Further the used table can be changed.
[SQL Scheme](sql-scheme) shows the basic tables which can be extended by further columns if needed. The 'salt' column in the user table is optional.
	
