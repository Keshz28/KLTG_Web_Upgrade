-- Merchandise Orders / Checkout Migration
-- Run this ONCE in phpMyAdmin or MySQL CLI (after db_migration_merchandise.sql).
-- Adds: product description, a store-settings row (WhatsApp number + payment QR),
-- and the customer orders table that the public checkout (order.php) writes to.

-- 1) Product description (shown on the merchandise card + checkout).
--    NOTE: no "IF NOT EXISTS" on ADD COLUMN in older MySQL — run this file only once.
ALTER TABLE merchandise ADD COLUMN description TEXT NULL AFTER name;

-- 2) Single-row store settings: the business WhatsApp number orders are sent to,
--    and the filename of the uploaded DuitNow/bank payment QR shown at checkout.
CREATE TABLE IF NOT EXISTS merchandise_settings (
  id              INT          NOT NULL PRIMARY KEY DEFAULT 1,
  whatsapp_number VARCHAR(30)  NOT NULL DEFAULT '',
  payment_qr      VARCHAR(255) NOT NULL DEFAULT ''
);
INSERT IGNORE INTO merchandise_settings (id, whatsapp_number, payment_qr) VALUES (1, '', '');

-- 3) Customer orders. product_name/price are snapshotted so an order still reads
--    correctly even if the product is later edited or deleted.
CREATE TABLE IF NOT EXISTS merchandise_orders (
  order_id         INT          AUTO_INCREMENT PRIMARY KEY,
  merchandise_id   INT          NULL DEFAULT NULL,
  product_name     VARCHAR(255) NOT NULL DEFAULT '',
  product_price    VARCHAR(50)  NOT NULL DEFAULT '',
  customer_name    VARCHAR(255) NOT NULL,
  customer_email   VARCHAR(255) NOT NULL,
  customer_phone   VARCHAR(50)  NOT NULL,
  customer_address TEXT         NOT NULL,
  receipt_image    VARCHAR(255) NOT NULL DEFAULT '',
  status           VARCHAR(30)  NOT NULL DEFAULT 'pending',
  created_at       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_mo_merch (merchandise_id)
);
