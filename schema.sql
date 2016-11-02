-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NOT NULL ,
  `first_name` VARCHAR(255) NOT NULL ,
  `last_name` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(65) NOT NULL ,
  `salt` VARCHAR(255) DEFAULT NULL ,
  -- Additional fields can be added ...
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `uniq_email` (`email` ASC))
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `user_roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(32) NULL ,
  -- Additional fields can be added ...
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `uniq_name` (`name` ASC));

-- -----------------------------------------------------
-- Table `users_user_roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users_user_roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) UNSIGNED NOT NULL ,
  `user_role_id` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_users_user_roles_user_id` (`user_id` ASC) ,
  INDEX `fk_users_user_roles_user_role_id` (`user_role_id` ASC) ,
  CONSTRAINT `fk_users_user_roles_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_user_roles_user_role_id`
    FOREIGN KEY (`user_role_id`)
    REFERENCES `user_roles` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

-- -----------------------------------------------------
-- Table `user_logins`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_logins` (
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
CREATE TABLE IF NOT EXISTS `user_tokens` (
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
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;