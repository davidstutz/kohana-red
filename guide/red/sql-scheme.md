# SQL Scheme

Both for the group and the user additional required fields can be added. first_name, last_name and salt are optional. If the salt column does not exist user salts will not be used. first_name and last_name could be replaced by a username or similar. But note that login is currently only supported with email.

	-- -----------------------------------------------------
	-- Table `user_roles`
	-- -----------------------------------------------------
	CREATE  TABLE `user_groups` (
	  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `name` VARCHAR(32) NULL ,
	  -- Additional fields can be added ...
	  PRIMARY KEY (`id`) ,
	  UNIQUE INDEX `uniq_name` (`name` ASC) );
	
	
	-- -----------------------------------------------------
	-- Table `users`
	-- -----------------------------------------------------
	CREATE  TABLE `users` (
	  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `email` VARCHAR(255) NOT NULL ,
	  `first_name` VARCHAR(255) NOT NULL ,
	  `last_name` VARCHAR(255) NOT NULL ,
	  `password` VARCHAR(65) NOT NULL ,
  	  `salt` VARCHAR(255) DEFAULT NULL ,
	  -- Additional fields can be added ...
	  PRIMARY KEY (`id`) ,
	  UNIQUE INDEX `uniq_email` (`email` ASC) )
	DEFAULT CHARACTER SET = utf8;
	
	
	-- -----------------------------------------------------
    -- Table `users_user_roles`
    -- -----------------------------------------------------
    CREATE TABLE IF NOT EXISTS `users_user_groups` (
      `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
      `user_id` INT(11) UNSIGNED NOT NULL ,
      `user_role_id` INT(11) UNSIGNED NOT NULL ,
      PRIMARY KEY (`id`) ,
      INDEX `fk_users_user_groups_user_id` (`user_id` ASC) ,
      INDEX `fk_users_user_groups_user_role_id` (`user_role_id` ASC) ,
      CONSTRAINT `fk_users_user_groups_user_id`
        FOREIGN KEY (`user_id` )
        REFERENCES `users` (`id` )
        ON DELETE CASCADE
        ON UPDATE NO ACTION,
      CONSTRAINT `fk_users_user_groups_user_role_id`
        FOREIGN KEY (`user_role_id` )
        REFERENCES `user_roles` (`id` )
        ON DELETE CASCADE
        ON UPDATE NO ACTION)
    DEFAULT CHARACTER SET = utf8
    COLLATE = utf8_general_ci;

	
	-- -----------------------------------------------------
	-- Table `user_logins`
	-- -----------------------------------------------------
	CREATE  TABLE `user_logins` (
	  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `ip` VARCHAR(65) NOT NULL ,
	  `agent` VARCHAR(65) NOT NULL ,
	  `login` VARCHAR(255) NOT NULL ,
	  `created` INT(11) UNSIGNED NOT NULL ,
	  `user_id` INT(11) DEFAULT NULL ,
	  PRIMARY KEY (`id`))
	DEFAULT CHARACTER SET = utf8;
	
	-- -----------------------------------------------------
	-- Table `user_tokens`
	-- -----------------------------------------------------
	CREATE TABLE IF NOT EXISTS `pl_user_tokens` (
	  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `token` VARCHAR(40) NOT NULL ,
	  `user_id` INT(11) UNSIGNED NOT NULL ,
	  `user_agent` VARCHAR(64) NOT NULL ,
	  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ,
	  `expires` INT(11) UNSIGNED NOT NULL ,
	  PRIMARY KEY (`id`) ,
	  UNIQUE INDEX `uniq_token` (`token`) ,
	  INDEX `fk_user_tokens_user_id` (`user_id` ASC) ,
	  CONSTRAINT `fk_user_tokens_user_id`
	    FOREIGN KEY (`user_id` )
	    REFERENCES `pl_users` (`id` )
	    ON DELETE CASCADE
	    ON UPDATE NO ACTION)
	DEFAULT CHARACTER SET = utf8
	COLLATE = utf8_general_ci;