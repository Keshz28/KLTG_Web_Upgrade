-- Merchandise Migration
-- Run this once in phpMyAdmin or MySQL CLI before deploying the merchandise admin editor.
-- Creates the merchandise + category tables and seeds the default "KL The Guide" category.

CREATE TABLE IF NOT EXISTS merchandise_category (
  merchandise_category_id    INT AUTO_INCREMENT PRIMARY KEY,
  name                       VARCHAR(255) NOT NULL,
  merchandise_category_order INT          NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS merchandise (
  merchandise_id     INT AUTO_INCREMENT PRIMARY KEY,
  name               VARCHAR(255)  NOT NULL,
  image              VARCHAR(255)  NOT NULL DEFAULT '',
  category_id        INT           NULL     DEFAULT NULL,
  price              VARCHAR(50)   NOT NULL DEFAULT '',
  buy_url            VARCHAR(1000) NOT NULL DEFAULT '',
  merchandise_order  INT           NOT NULL DEFAULT 0,
  INDEX idx_merch_category (category_id)
);

-- Seed the one category that exists today. More can be added from the admin panel.
INSERT INTO merchandise_category (name, merchandise_category_order)
SELECT 'KL The Guide', 0
WHERE NOT EXISTS (SELECT 1 FROM merchandise_category WHERE name = 'KL The Guide');
