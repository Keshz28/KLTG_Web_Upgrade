-- ============================================================================
--  mapcoords_migration_plain.sql
--
--  Fallback for mapcoords_migration.sql, for a DB user without CREATE ROUTINE.
--  Same 26 columns, plain ALTER TABLE, no stored procedure.
--
--  NOT re-runnable: a column that already exists raises "Duplicate column
--  name" and phpMyAdmin then aborts the rest of the file. Only use this on a
--  database where the verification count came back 0.
-- ============================================================================

ALTER TABLE `explorekl_wtd` ADD COLUMN `explorekl_wtd_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `explorekl_hs` ADD COLUMN `explorekl_hs_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `explorekl_kl4k` ADD COLUMN `explorekl_kl4k_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `explorekl_p` ADD COLUMN `explorekl_p_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `explorekl_pwor` ADD COLUMN `explorekl_pwor_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `explorekl_nl` ADD COLUMN `explorekl_nl_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `explorekl_ss` ADD COLUMN `explorekl_ss_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `explorekl_wte_sf` ADD COLUMN `explorekl_wte_sf_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `explorekl_wte_c` ADD COLUMN `explorekl_wte_c_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `explorekl_wte_r` ADD COLUMN `explorekl_wte_r_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `beyondkl_i` ADD COLUMN `beyondkl_i_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `beyondkl_hs` ADD COLUMN `beyondkl_hs_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `beyondkl_w` ADD COLUMN `beyondkl_w_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `beyondkl_h` ADD COLUMN `beyondkl_h_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `beyondkl_es` ADD COLUMN `beyondkl_es_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `medical_tourism_hc` ADD COLUMN `medical_tourism_hc_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `medical_tourism_dtl` ADD COLUMN `medical_tourism_dtl_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `medical_tourism_der` ADD COLUMN `medical_tourism_der_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `medical_tourism_oph` ADD COLUMN `medical_tourism_oph_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `medical_tourism_ps` ADD COLUMN `medical_tourism_ps_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `place_shop` ADD COLUMN `place_shop_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `spa` ADD COLUMN `spa_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `accommodation_top` ADD COLUMN `accommodation_top_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `accommodation_h` ADD COLUMN `accommodation_h_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `accommodation_bh` ADD COLUMN `accommodation_bh_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
ALTER TABLE `accommodation_bks` ADD COLUMN `accommodation_bks_mapcoords` VARCHAR(64) NULL DEFAULT NULL;
