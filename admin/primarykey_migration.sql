-- ============================================================================
--  primarykey_migration.sql
--
--  Gives every place table a real PRIMARY KEY with AUTO_INCREMENT.
--
--  WHY THIS IS THE BIG ONE
--  -----------------------
--  Eleven of the twenty-six place tables were created as
--      `<prefix>_id` int(11) NOT NULL
--  with NO primary key and NO auto-increment. Nothing ever generated an id, so
--  every row added through the CMS was written with `<prefix>_id = 0`.
--
--  Because the edit and delete modals identify a row purely by that id, the
--  consequences were:
--    * "Add New" appeared to work, but the new row shared id 0 with every other
--      row added since;
--    * clicking the pen on ANY of them opened the same record;
--    * "Save Changes" ran  UPDATE ... WHERE id = 0  and rewrote ALL of them;
--    * "Delete" ran  DELETE ... WHERE id = 0  and removed ALL of them.
--
--  Worst affected as found: explorekl_wte_c (all 10 Cafes on id 0) and the four
--  accommodation tables (every id duplicated).
--
--  WHAT THIS DOES
--  --------------
--  1. Renumbers each affected table 1..N in its current id order, so no two rows
--     share an id. Row ORDER and all content are untouched.
--  2. Adds PRIMARY KEY + AUTO_INCREMENT so new rows get their own id from now on.
--
--  Renumbering is safe: these ids are not referenced anywhere else. They exist
--  only inside the admin markup (regenerated on each page load) and in
--  admin/reorder.php's per-request payload. No foreign keys, no public URLs.
--
--  Apply once, by hand (phpMyAdmin or mysql CLI). TAKE A DB BACKUP FIRST.
--  Safe to re-run: renumbering is idempotent, and the ALTERs will simply error
--  as "Multiple primary key defined" on a table that has already been done.
--
--  MEMO for the next dev — full file map is in PROJECT_GUIDE.md
-- ============================================================================

-- ---------- Explore KL ----------
SET @i := 0;
UPDATE explorekl_hs SET explorekl_hs_id = (@i := @i + 1) ORDER BY explorekl_hs_id;
ALTER TABLE explorekl_hs MODIFY explorekl_hs_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

SET @i := 0;
UPDATE explorekl_pwor SET explorekl_pwor_id = (@i := @i + 1) ORDER BY explorekl_pwor_id;
ALTER TABLE explorekl_pwor MODIFY explorekl_pwor_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

SET @i := 0;
UPDATE explorekl_wte_sf SET explorekl_wte_sf_id = (@i := @i + 1) ORDER BY explorekl_wte_sf_id;
ALTER TABLE explorekl_wte_sf MODIFY explorekl_wte_sf_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- All ten rows here were sharing id 0; order by the display order so the
-- renumbering follows what the admin actually sees on the page.
SET @i := 0;
UPDATE explorekl_wte_c SET explorekl_wte_c_id = (@i := @i + 1) ORDER BY explorekl_wte_c_order;
ALTER TABLE explorekl_wte_c MODIFY explorekl_wte_c_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

SET @i := 0;
UPDATE explorekl_wte_r SET explorekl_wte_r_id = (@i := @i + 1) ORDER BY explorekl_wte_r_id, explorekl_wte_r_order;
ALTER TABLE explorekl_wte_r MODIFY explorekl_wte_r_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- ---------- Medical Tourism ----------
SET @i := 0;
UPDATE medical_tourism_ps SET medical_tourism_ps_id = (@i := @i + 1) ORDER BY medical_tourism_ps_id;
ALTER TABLE medical_tourism_ps MODIFY medical_tourism_ps_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- ---------- Spa ----------
SET @i := 0;
UPDATE spa SET spa_id = (@i := @i + 1) ORDER BY spa_id;
ALTER TABLE spa MODIFY spa_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- ---------- Place To Stay ----------
-- NOTE: these four also contain exact duplicate ROWS (every entry appears twice,
-- identical in every column). This migration only makes the ids unique so the
-- editors can address one row at a time; it deliberately does NOT delete
-- anything. Run admin/accommodation_dedupe.sql separately if you want the
-- duplicates removed.
SET @i := 0;
UPDATE accommodation_top SET accommodation_top_id = (@i := @i + 1) ORDER BY accommodation_top_id;
ALTER TABLE accommodation_top MODIFY accommodation_top_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

SET @i := 0;
UPDATE accommodation_h SET accommodation_h_id = (@i := @i + 1) ORDER BY accommodation_h_id;
ALTER TABLE accommodation_h MODIFY accommodation_h_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

SET @i := 0;
UPDATE accommodation_bh SET accommodation_bh_id = (@i := @i + 1) ORDER BY accommodation_bh_id;
ALTER TABLE accommodation_bh MODIFY accommodation_bh_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

SET @i := 0;
UPDATE accommodation_bks SET accommodation_bks_id = (@i := @i + 1) ORDER BY accommodation_bks_id;
ALTER TABLE accommodation_bks MODIFY accommodation_bks_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- ---------- Section navigation tiles ----------
-- Same defect: a tile added from the CMS got id 0, so it could never afterwards
-- be edited or deleted from the panel.
SET @i := 0;
UPDATE explorekl_nav SET id = (@i := @i + 1) ORDER BY id;
ALTER TABLE explorekl_nav MODIFY id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

SET @i := 0;
UPDATE beyondkl_nav SET id = (@i := @i + 1) ORDER BY id;
ALTER TABLE beyondkl_nav MODIFY id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

SET @i := 0;
UPDATE accommodation_nav SET id = (@i := @i + 1) ORDER BY id;
ALTER TABLE accommodation_nav MODIFY id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

SET @i := 0;
UPDATE medical_tourism_nav SET id = (@i := @i + 1) ORDER BY id;
ALTER TABLE medical_tourism_nav MODIFY id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;
