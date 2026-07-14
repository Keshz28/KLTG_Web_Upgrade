-- ============================================================================
--  db_migration_devpanel.sql
--
--  Schema for the hidden DevPanel (xp.php).
--  Apply by hand (phpMyAdmin / mysql CLI), same pattern as the other
--  db_migration_*.sql files in this repo.
--
--    "d:/xampp/mysql/bin/mysql.exe" -u root <db_name> < db_migration_devpanel.sql
-- ============================================================================

-- IP addresses for which advertisements are hidden site-wide.
-- The public pages (via header.php) check the visitor's IP against this table
-- and suppress all AdSense slots when it matches. Everyone else still sees ads.
CREATE TABLE IF NOT EXISTS `devpanel_ad_block` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address`  VARCHAR(45)  NOT NULL,            -- IPv4 or IPv6
  `note`        VARCHAR(255)     NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ip` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
