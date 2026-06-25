-- Advertisement popup table
-- Run manually via phpMyAdmin or mysql CLI
CREATE TABLE IF NOT EXISTS `advertisement` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `image` VARCHAR(255) NOT NULL DEFAULT '',
    `link_url` VARCHAR(500) NOT NULL DEFAULT '',
    `is_active` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
