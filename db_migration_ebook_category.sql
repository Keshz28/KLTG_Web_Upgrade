-- ============================================================================
--  db_migration_ebook_category.sql
--
--  Moves the e-book category list out of hard-coded PHP and into a table so it
--  can be managed from the admin dashboard (Edit Pages -> E-book).
--
--  Apply by hand (phpMyAdmin / mysql CLI). Safe to re-run: uses IF NOT EXISTS
--  and INSERT IGNORE keyed on the unique cat_code.
--
--  Each cat_code is ALSO the folder name used for that category's files:
--      assets/pdf/ebook/<cat_code>/   (the PDF e-books)
--      assets/img/ebook/<cat_code>/   (the cover images / PDFs)
--
--  cat_visible = 1 -> shown as a tab on the public ebook.php page
--  cat_visible = 0 -> selectable in admin but hidden from the public page
-- ============================================================================

CREATE TABLE IF NOT EXISTS `ebook_category` (
    `cat_id`      INT NOT NULL AUTO_INCREMENT,
    `cat_code`    VARCHAR(50)  NOT NULL,
    `cat_title`   VARCHAR(255) NOT NULL,
    `cat_desc`    TEXT NULL,
    `cat_order`   INT NOT NULL DEFAULT 0,
    `cat_visible` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`cat_id`),
    UNIQUE KEY `uniq_cat_code` (`cat_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --- Visible categories (the 13 currently shown on the live site) -----------
INSERT IGNORE INTO `ebook_category` (`cat_code`, `cat_title`, `cat_desc`, `cat_order`, `cat_visible`) VALUES
('kltg', 'KL The Guide', 'Unlock the ultimate trip to Kuala Lumpur. Our exclusive KL The Guide eBooks pack everything you need into one place. Detailed itineraries, local secrets and cultural deep dives.', 1, 1),
('kv4l', 'Klang Valley 4 Locals', 'The Klang Valley 4 Locals eBooks are exclusive guides specially crafted for locals, helping them to uncover the hidden gems and unique experiences within the vibrant Klang Valley region. Whether you''re a local looking to discover the facets of Klang Valley or a visitor seeking an authentic local experience, the Klang Valley 4 Locals eBooks are your ultimate guide.', 2, 1),
('mktg', 'Melaka The Guide', 'Experience the enchanting city of Melaka like never before with Melaka The Guide eBooks! Our meticulously crafted guides offer the best recommendations and insights for those seeking to explore the rich history and cultural wonders of the beautiful historical city.', 3, 1),
('tptg', 'Taiping The Guide', 'Discover the charming town of Taiping with Taiping The Guide eBooks. Let Taiping The Guide eBooks be your companion as you embark on a remarkable journey through Taiping''s storied past and vibrant present.', 4, 1),
('uztg', 'Uzbekistan The Guide', 'Embark on a remarkable journey to the enchanting country of Uzbekistan with Uzbekistan The Guide eBook. With just a tap of your finger, our comprehensive eBook allows you to plan and explore this beautiful country with ease.', 5, 1),
('kntg', 'Keningau The Guide', 'Discover the ultimate travel companion for your upcoming journey to Keningau! Check our Keningau The Guide eBook, a comprehensive travel guide to help you plan and make the most of your visit to this captivating destination.', 6, 1),
('twtg', 'Tawau The Guide', 'With one click, unlock a treasure trove of invaluable information and expert recommendations with Tawau The Guide eBook! Whether you''re a first-time visitor or a seasoned traveler, let ''Tawau The Guide'' be your trusted companion, providing you with all the tools you need to create unforgettable memories in the mesmerizing Tawau town.', 7, 1),
('tbtg', 'Tambunan The Guide', 'Immerse yourself in the rich culture, vibrant traditions, and breathtaking landscapes that Tambunan has to offer. From must-see attractions and hidden gems to local dining spots and off-the-beaten-path adventures, Tambunan The Guide eBook has it all!', 8, 1),
('hstg', 'Hulu Selangor The Guide', 'Immerse yourself in the natural beauty, historical landmarks, and cultural richness of this hidden gem. Our meticulously curated eBooks provide the best guides and recommendations for those looking to explore Hulu Selangor''s unique offerings.', 9, 1),
('prtg', 'Perak The Guide', 'Embark on an extraordinary journey through Perak''s wonders with Perak The Guide eBooks, your ultimate companion for an unforgettable experience at this beautiful state.', 10, 1),
('sbtg', 'Seremban The Guide', 'Unlock the wonders of Seremban with Seremban The Guide eBook. Discover this charming city and everything that Seremban has to offer, right at your fingertips. Experience Seremban like a local with Seremban The Guide eBook, your virtual companion to this captivating city.', 11, 1),
('kstg', 'Kuala Selangor The Guide', 'Discover Kuala Selangor like never before with our comprehensive Kuala Selangor The Guide eBook! Our eBook simplifies your trip planning process, making it a breeze to organize your next unforgettable journey with just a click away.', 12, 1),
('klgt', 'Kuala Langat The Guide', 'Explore the enchanting town of Kuala Langat with Kuala Langat The Guide. Inside our eBook, you''ll find everything you need to know to make the most of your trip in Kuala Langat!', 13, 1);

-- --- Extra categories that existed in the admin dropdown but were not shown --
-- --- on the live site. Seeded hidden so the public page is unchanged.      --
INSERT IGNORE INTO `ebook_category` (`cat_code`, `cat_title`, `cat_desc`, `cat_order`, `cat_visible`) VALUES
('kltgs', 'KL The Guide Specials', '', 14, 0),
('kztg',  'Kazakhstan The Guide',  '', 15, 0),
('amspa', 'AMSPA',                 '', 16, 0),
('kgtg',  'Klang The Guide',       '', 17, 0);
