# SQL Scheme

Both for the group and the user additional required fields can be added. first_name, last_name are optional and could be replaced by a username.

	-- -----------------------------------------------------
	-- Table `pl_user_groups`
	-- -----------------------------------------------------
	CREATE  TABLE `pl_user_groups` (
	  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `name` VARCHAR(32) NULL ,
	  `description` TEXT NULL ,
	  PRIMARY KEY (`id`) ,
	  UNIQUE INDEX `uniq_name` (`name` ASC) );
	
	
	-- -----------------------------------------------------
	-- Table `pl_users`
	-- -----------------------------------------------------
	CREATE  TABLE `pl_users` (
	  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `email` VARCHAR(255) NOT NULL ,
	  `first_name` VARCHAR(255) NOT NULL ,
	  `last_name` VARCHAR(255) NOT NULL ,
	  `password` VARCHAR(65) NOT NULL ,
	  -- Additional fields can be added.
	  `group_id` INT(11) UNSIGNED NULL ,
	  PRIMARY KEY (`id`) ,
	  FULLTEXT (`email`, `first_name`, `last_name`) ,
	  UNIQUE INDEX `uniq_email` (`email` ASC) ,
	  INDEX `fk_users_group_id` (`group_id` ASC) ,
	  CONSTRAINT `fk_users_group_id`
	    FOREIGN KEY (`group_id` )
	    REFERENCES `pl_user_groups` (`id` )
	    ON DELETE NO ACTION
	    ON UPDATE NO ACTION)
	DEFAULT CHARACTER SET = utf8;
	
	
	-- -----------------------------------------------------
	-- Table `pl_user_logins`
	-- -----------------------------------------------------
	CREATE  TABLE `pl_user_logins` (
	  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `ip` VARCHAR(65) NOT NULL ,
	  `agent` VARCHAR(65) NOT NULL ,
	  `login` VARCHAR(255) NOT NULL ,
	  `time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
	  PRIMARY KEY (`id`))
	DEFAULT CHARACTER SET = utf8;
	
	-- -----------------------------------------------------
	-- Table `pl_user_tokens`
	-- -----------------------------------------------------
	CREATE  TABLE `pl_user_tokens` (
	  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	  `user_id` INT(11) UNSIGNED NULL ,
	  `user_agent` VARCHAR(40) NOT NULL ,
	  `token` VARCHAR(40) NOT NULL ,
	  `type` VARCHAR(100) NOT NULL ,
	  `created` INT(11) UNSIGNED NOT NULL ,
	  `expires` INT(11) UNSIGNED NOT NULL ,
	  PRIMARY KEY (`id`) ,
	  UNIQUE INDEX `uniq_token` (`token` ASC) ,
	  INDEX `fk_user_tokens_user_id` (`user_id` ASC) ,
	  CONSTRAINT `fk_user_tokens_user_id`
	    FOREIGN KEY (`user_id` )
	    REFERENCES `pl_users` (`id` )
	    ON DELETE CASCADE)
	DEFAULT CHARACTER SET = utf8;