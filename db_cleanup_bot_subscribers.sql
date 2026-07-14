-- ============================================================================
--  Bot subscriber cleanup + legacy-list repair for `emailsub`
--
--  Run this MANUALLY in phpMyAdmin / MySQL CLI against production
--  (kltheguidecom_bluedale2_kltg), ONE STEP AT A TIME, reviewing each
--  preview SELECT before running the DELETE beneath it.
--
--  Context: the site moved to SINGLE opt-in (admin/sub_handler.php inserts new
--  real subscribers with verified=1). Spam bots had been flooding `emailsub`
--  through an old unprotected code path (now removed) and by hammering the
--  sign-up endpoint. This script removes the obvious bot rows and, optionally,
--  makes the surviving legacy list mailable again.
--
--  ⚠️ ALWAYS take a backup first:
--     mysqldump -u USER -p kltheguidecom_bluedale2_kltg emailsub > emailsub_backup.sql
-- ============================================================================


-- ----------------------------------------------------------------------------
-- STEP 1 — See the damage: Gmail rows grouped by number of dots in the local
-- part. Bot spam uses many dots (Gmail ignores them). Real addresses almost
-- never have 4+ dots. Look before you delete.
-- ----------------------------------------------------------------------------
SELECT
  (LENGTH(SUBSTRING_INDEX(emailsub_email,'@',1))
   - LENGTH(REPLACE(SUBSTRING_INDEX(emailsub_email,'@',1),'.',''))) AS dots,
  COUNT(*) AS rows_count
FROM emailsub
WHERE emailsub_email LIKE '%@gmail.com'
GROUP BY dots
ORDER BY dots;


-- ----------------------------------------------------------------------------
-- STEP 2 — PREVIEW the high-confidence bot rows (Gmail local part with 4+ dots).
-- This is the SAFE threshold. Eyeball the list; these should look machine-made.
-- ----------------------------------------------------------------------------
SELECT emailsub_id, emailsub_email, emailsub_country, emailsub_date
FROM emailsub
WHERE emailsub_email LIKE '%@gmail.com'
  AND (LENGTH(SUBSTRING_INDEX(emailsub_email,'@',1))
       - LENGTH(REPLACE(SUBSTRING_INDEX(emailsub_email,'@',1),'.',''))) >= 4
ORDER BY emailsub_id DESC;

-- STEP 2b — DELETE them once the preview looks right:
-- DELETE FROM emailsub
-- WHERE emailsub_email LIKE '%@gmail.com'
--   AND (LENGTH(SUBSTRING_INDEX(emailsub_email,'@',1))
--        - LENGTH(REPLACE(SUBSTRING_INDEX(emailsub_email,'@',1),'.',''))) >= 4;


-- ----------------------------------------------------------------------------
-- STEP 3 — (OPTIONAL, more aggressive) 3-dot Gmail rows. Some of these can be
-- real small-business addresses, so REVIEW this preview carefully and only run
-- the delete if you're comfortable. Lower/raise the >= 3 threshold to taste.
-- ----------------------------------------------------------------------------
SELECT emailsub_id, emailsub_email, emailsub_country, emailsub_date
FROM emailsub
WHERE emailsub_email LIKE '%@gmail.com'
  AND (LENGTH(SUBSTRING_INDEX(emailsub_email,'@',1))
       - LENGTH(REPLACE(SUBSTRING_INDEX(emailsub_email,'@',1),'.',''))) = 3
ORDER BY emailsub_id DESC;

-- STEP 3b — DELETE (only after reviewing STEP 3):
-- DELETE FROM emailsub
-- WHERE emailsub_email LIKE '%@gmail.com'
--   AND (LENGTH(SUBSTRING_INDEX(emailsub_email,'@',1))
--        - LENGTH(REPLACE(SUBSTRING_INDEX(emailsub_email,'@',1),'.',''))) = 3;


-- ----------------------------------------------------------------------------
-- STEP 4 — De-duplicate, then add a DB-level uniqueness guard so the same
-- address can never be stored twice again. (Same as db_migration_email_dedupe.sql
-- — skip if you already ran that file on prod.)
-- ----------------------------------------------------------------------------
-- Keep one row per email: prefer verified, else the oldest id.
DELETE t1 FROM emailsub t1
JOIN emailsub t2
  ON t1.emailsub_email = t2.emailsub_email
 AND t1.emailsub_id <> t2.emailsub_id
WHERE t2.verified > t1.verified
   OR (t2.verified = t1.verified AND t2.emailsub_id < t1.emailsub_id);

-- Add the unique index (191-char prefix: emailsub_email is TEXT under utf8mb4).
-- If this errors with "Duplicate entry", the dedupe above didn't fully run — re-run it.
ALTER TABLE emailsub
  ADD UNIQUE INDEX idx_emailsub_email_unique (emailsub_email(191));


-- ----------------------------------------------------------------------------
-- STEP 5 — (OPTIONAL) Make the surviving LEGACY list mailable again.
--
-- Every existing row is verified=0, and the newsletter blast only sends to
-- verified=1 — so right now it reaches NOBODY. If you want your historical
-- subscribers to receive newsletters again, flip the survivors to verified=1
-- AFTER you have removed the bots in steps 2-3.
--
-- ⚠️ Deliverability warning: these addresses were collected over years and
-- never re-confirmed. Blasting all of them at once can hurt your sending
-- reputation. Consider emailing in smaller batches and honouring unsubscribes.
--
-- Preview how many rows this affects first:
SELECT COUNT(*) AS will_be_activated FROM emailsub WHERE verified = 0;

-- Then, if you accept the trade-off:
-- UPDATE emailsub SET verified = 1 WHERE verified = 0;
