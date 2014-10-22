CREATE TABLE IF NOT EXISTS `#__keymanager_requests` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`requester_username` VARCHAR(255)  NOT NULL ,
`department_head_email` VARCHAR(255)  NOT NULL ,
`department_head_token` VARCHAR(255)  NOT NULL ,
`department_head_approved_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`vice_president_email` VARCHAR(255)  NOT NULL ,
`vice_president_token` VARCHAR(255)  NOT NULL ,
`vice_president_approved_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`access_card` VARCHAR(255)  NOT NULL ,
`issued_date` DATE NOT NULL ,
`created_date` DATETIME NOT NULL ,
`can_pickup` VARCHAR(255)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__keymanager_request_keys` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`request_id` INT NOT NULL ,
`key_id` INT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`pickup_date` DATE NOT NULL ,
`returned_date` DATE NOT NULL ,
`lost_date` DATE NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__keymanager_cabinets` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`cabinet_name` VARCHAR(255)  NOT NULL ,
`cabinet_description` VARCHAR(255)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__keymanager_hooks` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`hook_number` VARCHAR(50)  NOT NULL ,
`cabinet_id` INT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`hook_created_date` DATETIME NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__keymanager_buildings` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`building_name` VARCHAR(100)  NOT NULL ,
`building_description` VARCHAR(255)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__keymanager_rooms` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`room_name` VARCHAR(255)  NOT NULL ,
`room_description` VARCHAR(255)  NOT NULL ,
`building_id` INT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__keymanager_keys` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`key_name` VARCHAR(100)  NOT NULL ,
`key_description` VARCHAR(255)  NOT NULL ,
`hook_id` INT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`building_id` INT NOT NULL ,
`is_master_key` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__keymanager_key_rooms` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`key_id` INT NOT NULL ,
`room_id` INT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

