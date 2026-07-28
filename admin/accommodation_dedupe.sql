-- ============================================================================
--  accommodation_dedupe.sql        *** THIS ONE DELETES ROWS — READ FIRST ***
--
--  Every row in the four Place To Stay tables exists TWICE. The copies are
--  identical in every column, so the public accommodation.php page lists each
--  hotel twice, and the admin table shows each entry twice.
--
--  As found (local copy of the production DB):
--      accommodation_top   10 rows ->  5 distinct
--      accommodation_h     28 rows -> 14 distinct
--      accommodation_bh    32 rows -> 16 distinct
--      accommodation_bks   24 rows -> 12 distinct
--                          --------     --------
--                          94 rows      47 distinct   (47 rows would be removed)
--
--  This looks like a data import that ran twice. It is NOT caused by the CMS.
--
--  DO NOT run this blind:
--    1. Take a database backup.
--    2. Run primarykey_migration.sql FIRST — without unique ids the DELETEs
--       below cannot tell the two copies apart and would remove both.
--    3. Run the PREVIEW block and check the numbers against your own data.
--    4. Only then run the DELETE block.
--
--  Keeping the duplicates is also a valid choice: once the ids are unique you
--  can simply delete the extra copies from the admin panel one at a time.
--
--  MEMO for the next dev — full file map is in PROJECT_GUIDE.md
-- ============================================================================


-- ---------------------------------------------------------------- PREVIEW ---
-- Run this on its own first. "will_delete" is how many rows the block below
-- would remove from each table. If will_delete is 0, there is nothing to do.

SELECT 'accommodation_top' AS table_name,
       COUNT(*) AS total_rows,
       COUNT(*) - COUNT(DISTINCT accommodation_top_title, accommodation_top_content,
                        accommodation_top_location, accommodation_top_image) AS will_delete
FROM accommodation_top
UNION ALL
SELECT 'accommodation_h', COUNT(*),
       COUNT(*) - COUNT(DISTINCT accommodation_h_title, accommodation_h_content,
                        accommodation_h_location, accommodation_h_image)
FROM accommodation_h
UNION ALL
SELECT 'accommodation_bh', COUNT(*),
       COUNT(*) - COUNT(DISTINCT accommodation_bh_title, accommodation_bh_content,
                        accommodation_bh_location, accommodation_bh_image)
FROM accommodation_bh
UNION ALL
SELECT 'accommodation_bks', COUNT(*),
       COUNT(*) - COUNT(DISTINCT accommodation_bks_title, accommodation_bks_content,
                        accommodation_bks_location, accommodation_bks_image)
FROM accommodation_bks;


-- ----------------------------------------------------------------- DELETE ---
-- Keeps the LOWEST id of each duplicate group and removes the rest. Requires
-- primarykey_migration.sql to have been applied (unique ids).
--
-- Uncomment the four statements below to run them.

-- DELETE a FROM accommodation_top a
--   JOIN (SELECT MIN(accommodation_top_id) AS keep_id, accommodation_top_title,
--                accommodation_top_content, accommodation_top_location, accommodation_top_image
--         FROM accommodation_top GROUP BY 2,3,4,5) k
--     ON a.accommodation_top_title    <=> k.accommodation_top_title
--    AND a.accommodation_top_content  <=> k.accommodation_top_content
--    AND a.accommodation_top_location <=> k.accommodation_top_location
--    AND a.accommodation_top_image    <=> k.accommodation_top_image
--  WHERE a.accommodation_top_id > k.keep_id;

-- DELETE a FROM accommodation_h a
--   JOIN (SELECT MIN(accommodation_h_id) AS keep_id, accommodation_h_title,
--                accommodation_h_content, accommodation_h_location, accommodation_h_image
--         FROM accommodation_h GROUP BY 2,3,4,5) k
--     ON a.accommodation_h_title    <=> k.accommodation_h_title
--    AND a.accommodation_h_content  <=> k.accommodation_h_content
--    AND a.accommodation_h_location <=> k.accommodation_h_location
--    AND a.accommodation_h_image    <=> k.accommodation_h_image
--  WHERE a.accommodation_h_id > k.keep_id;

-- DELETE a FROM accommodation_bh a
--   JOIN (SELECT MIN(accommodation_bh_id) AS keep_id, accommodation_bh_title,
--                accommodation_bh_content, accommodation_bh_location, accommodation_bh_image
--         FROM accommodation_bh GROUP BY 2,3,4,5) k
--     ON a.accommodation_bh_title    <=> k.accommodation_bh_title
--    AND a.accommodation_bh_content  <=> k.accommodation_bh_content
--    AND a.accommodation_bh_location <=> k.accommodation_bh_location
--    AND a.accommodation_bh_image    <=> k.accommodation_bh_image
--  WHERE a.accommodation_bh_id > k.keep_id;

-- DELETE a FROM accommodation_bks a
--   JOIN (SELECT MIN(accommodation_bks_id) AS keep_id, accommodation_bks_title,
--                accommodation_bks_content, accommodation_bks_location, accommodation_bks_image
--         FROM accommodation_bks GROUP BY 2,3,4,5) k
--     ON a.accommodation_bks_title    <=> k.accommodation_bks_title
--    AND a.accommodation_bks_content  <=> k.accommodation_bks_content
--    AND a.accommodation_bks_location <=> k.accommodation_bks_location
--    AND a.accommodation_bks_image    <=> k.accommodation_bks_image
--  WHERE a.accommodation_bks_id > k.keep_id;
