-- Travel Tips CMS table (public page: travel-tips.php)
-- Holds the Q&A accordion "tip" items. The 5 sections (a–e) and their icons
-- remain fixed scaffolding in travel-tips.php; only the items are DB-driven.
-- Run manually via phpMyAdmin or mysql CLI, then apply traveltips_seed.sql
-- for the initial content.
CREATE TABLE IF NOT EXISTS `traveltips` (
    `tt_id`        INT(11)      NOT NULL AUTO_INCREMENT,
    `tt_section`   VARCHAR(4)   NOT NULL DEFAULT 'a',          -- a|b|c|d|e
    `tt_order`     INT(11)      NOT NULL DEFAULT 0,
    `tt_header`    VARCHAR(255) NOT NULL DEFAULT '',           -- e.g. "Mobile Services"
    `tt_question`  TEXT         NULL,                          -- the tt-q line
    `tt_answer`    TEXT         NULL,                          -- the tt-a paragraph
    `tt_extra`     TEXT         NULL,                          -- optional raw HTML (e.g. legal list)
    `tt_cta_type`  VARCHAR(10)  NOT NULL DEFAULT 'none',       -- none|link|map
    `tt_cta_label` VARCHAR(255) NULL,                          -- button/link text
    `tt_cta_value` VARCHAR(500) NULL,                          -- URL (link) or map search query (map)
    PRIMARY KEY (`tt_id`),
    KEY `idx_section_order` (`tt_section`, `tt_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
