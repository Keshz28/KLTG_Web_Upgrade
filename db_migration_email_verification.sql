-- Email Verification Migration
-- Run this once in phpMyAdmin or MySQL CLI before deploying the verification feature.

ALTER TABLE emailsub
  ADD COLUMN verified       TINYINT(1)   NOT NULL DEFAULT 0          AFTER emailsub_consent,
  ADD COLUMN verify_token   VARCHAR(64)  NULL     DEFAULT NULL        AFTER verified,
  ADD COLUMN verify_expires DATETIME     NULL     DEFAULT NULL        AFTER verify_token,
  ADD INDEX  idx_verify_token (verify_token);
