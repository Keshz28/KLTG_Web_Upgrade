-- ============================================================================
--  mapcoords_migration.sql
--
--  Adds a per-row "map coordinates" column to every place table behind the
--  Explore KL / Beyond KL / Medical Tourism / Places to Shop / Spa / Place to
--  Stay pages, and exposes it in the admin editors.
--
--  WHY: "View on Map" used to resolve a pin by *searching Google for the place
--  name*, with a small hand-maintained lookup (kltg_mapcoords.php,
--  beyondkl_mapcoords.php) covering roughly a third of the rows and keyed by the
--  exact title. Anything not in those files landed on whatever Google's text
--  search guessed — frequently the wrong venue — and renaming a place in the CMS
--  silently broke its pin. Nothing an admin could edit affected the map.
--
--  With this column the coordinate lives next to the row, the admin can paste or
--  correct it from the editor, and viewOnMapButton() prefers it over everything
--  else. Format is a plain "lat,lng" string, e.g. "3.1490605,101.6936592".
--  Empty/NULL keeps the old fallback behaviour, so this is safe to apply before
--  the values are filled in.
--
--  Apply once, by hand (phpMyAdmin or mysql CLI). Re-running is safe: each
--  statement is guarded, so a column that already exists is skipped.
--  Then apply mapcoords_seed.sql to fill in the resolved values.
--
--  MEMO for the next dev — full file map is in PROJECT_GUIDE.md
-- ============================================================================

DELIMITER //

DROP PROCEDURE IF EXISTS kltg_add_mapcoords //
CREATE PROCEDURE kltg_add_mapcoords(IN tbl VARCHAR(64), IN col VARCHAR(64))
BEGIN
  IF NOT EXISTS (
    SELECT 1 FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = tbl AND COLUMN_NAME = col
  ) THEN
    SET @s = CONCAT('ALTER TABLE `', tbl, '` ADD COLUMN `', col, '` VARCHAR(64) NULL DEFAULT NULL');
    PREPARE st FROM @s;
    EXECUTE st;
    DEALLOCATE PREPARE st;
  END IF;
END //

DELIMITER ;

-- Explore KL
CALL kltg_add_mapcoords('explorekl_wtd',    'explorekl_wtd_mapcoords');
CALL kltg_add_mapcoords('explorekl_hs',     'explorekl_hs_mapcoords');
CALL kltg_add_mapcoords('explorekl_kl4k',   'explorekl_kl4k_mapcoords');
CALL kltg_add_mapcoords('explorekl_p',      'explorekl_p_mapcoords');
CALL kltg_add_mapcoords('explorekl_pwor',   'explorekl_pwor_mapcoords');
CALL kltg_add_mapcoords('explorekl_nl',     'explorekl_nl_mapcoords');
CALL kltg_add_mapcoords('explorekl_ss',     'explorekl_ss_mapcoords');
CALL kltg_add_mapcoords('explorekl_wte_sf', 'explorekl_wte_sf_mapcoords');
CALL kltg_add_mapcoords('explorekl_wte_c',  'explorekl_wte_c_mapcoords');
CALL kltg_add_mapcoords('explorekl_wte_r',  'explorekl_wte_r_mapcoords');

-- Beyond KL
CALL kltg_add_mapcoords('beyondkl_i',  'beyondkl_i_mapcoords');
CALL kltg_add_mapcoords('beyondkl_hs', 'beyondkl_hs_mapcoords');
CALL kltg_add_mapcoords('beyondkl_w',  'beyondkl_w_mapcoords');
CALL kltg_add_mapcoords('beyondkl_h',  'beyondkl_h_mapcoords');
CALL kltg_add_mapcoords('beyondkl_es', 'beyondkl_es_mapcoords');

-- Medical Tourism
CALL kltg_add_mapcoords('medical_tourism_hc',  'medical_tourism_hc_mapcoords');
CALL kltg_add_mapcoords('medical_tourism_dtl', 'medical_tourism_dtl_mapcoords');
CALL kltg_add_mapcoords('medical_tourism_der', 'medical_tourism_der_mapcoords');
CALL kltg_add_mapcoords('medical_tourism_oph', 'medical_tourism_oph_mapcoords');
CALL kltg_add_mapcoords('medical_tourism_ps',  'medical_tourism_ps_mapcoords');

-- Places to Shop / Spa
CALL kltg_add_mapcoords('place_shop', 'place_shop_mapcoords');
CALL kltg_add_mapcoords('spa',        'spa_mapcoords');

-- Place to Stay (accommodation)
CALL kltg_add_mapcoords('accommodation_top', 'accommodation_top_mapcoords');
CALL kltg_add_mapcoords('accommodation_h',   'accommodation_h_mapcoords');
CALL kltg_add_mapcoords('accommodation_bh',  'accommodation_bh_mapcoords');
CALL kltg_add_mapcoords('accommodation_bks', 'accommodation_bks_mapcoords');

DROP PROCEDURE IF EXISTS kltg_add_mapcoords;
