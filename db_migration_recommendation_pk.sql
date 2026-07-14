-- ============================================================================
-- db_migration_recommendation_pk.sql
--
-- The `recommendation` table (Insider Suggestions on the homepage) has no
-- PRIMARY KEY / AUTO_INCREMENT on recommendation_id. Every INSERT that didn't
-- specify an id silently defaulted to 0, so multiple "New" rows collided on
-- id=0 — editing or deleting one such row affected ALL rows sharing that id.
--
-- recommendation_id is only an internal row identifier (NOT the Blogger post
-- id used for the public blog link — that's recommendation_postid, untouched
-- here), so it's safe to renumber every row sequentially and add a real
-- AUTO_INCREMENT primary key. No recommendation data is deleted.
--
-- Apply once (locally already applied; run this on prod via phpMyAdmin too).
-- ============================================================================

ALTER TABLE recommendation ADD COLUMN tmp_id INT AUTO_INCREMENT PRIMARY KEY;
UPDATE recommendation SET recommendation_id = tmp_id;
ALTER TABLE recommendation DROP COLUMN tmp_id;
ALTER TABLE recommendation
  MODIFY recommendation_id INT(11) NOT NULL AUTO_INCREMENT,
  ADD PRIMARY KEY (recommendation_id);
