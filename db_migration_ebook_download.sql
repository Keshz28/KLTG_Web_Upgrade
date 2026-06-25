-- E-book Download Counter Migration
-- Run this once in phpMyAdmin or MySQL CLI before deploying the download-count feature.
-- Adds a per-ebook download tally alongside the existing ebook_view columns.

ALTER TABLE ebook
  ADD COLUMN ebook_download INT NOT NULL DEFAULT 0;
