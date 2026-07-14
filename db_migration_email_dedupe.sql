-- Email Subscriber Deduplication + Uniqueness Migration
-- Run this once in phpMyAdmin or MySQL CLI against the production DB
-- (kltheguidecom_bluedale2_kltg). Cleans up duplicate emailsub_email rows that
-- accumulated before a DB-level uniqueness constraint existed, then adds that
-- constraint so it can't happen again.

-- OPTIONAL: run this first to see what will be affected before deleting anything.
-- SELECT emailsub_email, COUNT(*) AS copies
-- FROM emailsub
-- GROUP BY emailsub_email
-- HAVING COUNT(*) > 1;

-- Step 1: for each set of duplicate emails, keep exactly one row -- prefer a
-- verified row over an unverified one; if verified status matches, keep the
-- oldest (lowest emailsub_id) row. Delete every other row in the group.
DELETE t1 FROM emailsub t1
JOIN emailsub t2
  ON t1.emailsub_email = t2.emailsub_email
 AND t1.emailsub_id <> t2.emailsub_id
WHERE
  t2.verified > t1.verified
  OR (t2.verified = t1.verified AND t2.emailsub_id < t1.emailsub_id);

-- Step 2: prevent this from happening again at the DB level. Prefix length
-- (191) keeps the index under InnoDB's key-length limit on utf8mb4
-- (emailsub_email is TEXT, so a full-column unique index isn't allowed).
ALTER TABLE emailsub
  ADD UNIQUE INDEX idx_emailsub_email_unique (emailsub_email(191));
