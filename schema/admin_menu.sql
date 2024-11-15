CREATE TABLE `admin_menu` (
	`menu_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`parent_id` INT UNSIGNED NOT NULL DEFAULT '0',
	`name` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8mb4_0900_ai_ci',
	`route_name` VARCHAR(50) NOT NULL DEFAULT '' COLLATE 'utf8mb4_0900_ai_ci',
	`status` TINYINT UNSIGNED NOT NULL DEFAULT '0',
	`editor` VARCHAR(50) NOT NULL DEFAULT '_utf8mb4\\\'\\\'' COLLATE 'utf8mb4_0900_ai_ci',
	`deleted` TINYINT UNSIGNED NOT NULL DEFAULT '0',
	`add_time` DATETIME NOT NULL,
	`edit_time` DATETIME NOT NULL,
	`delete_time` DATETIME NOT NULL,
	PRIMARY KEY (`menu_id`) USING BTREE
)
COMMENT='后台管理菜单项目'
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;
