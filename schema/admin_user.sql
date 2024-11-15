CREATE TABLE `admin_user` (
	`admin_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`role_id` INT UNSIGNED NOT NULL DEFAULT '0',
	`name` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8mb4_0900_ai_ci',
	`password` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8mb4_0900_ai_ci',
	`ticket` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8mb4_0900_ai_ci',
	`expire_time` DATETIME NOT NULL,
	`status` TINYINT UNSIGNED NOT NULL DEFAULT '0',
	`editor` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8mb4_0900_ai_ci',
	`deleted` TINYINT UNSIGNED NOT NULL DEFAULT '0',
	`add_time` DATETIME NOT NULL,
	`edit_time` DATETIME NOT NULL,
	`delete_time` DATETIME NOT NULL,
	PRIMARY KEY (`admin_id`) USING BTREE,
	UNIQUE INDEX `name` (`name`) USING BTREE
)
COMMENT='后台管理员信息表'
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;
