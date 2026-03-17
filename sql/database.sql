-- ============================================================
--  Property Portal — Database Migration
--  File    : database.sql
--
--  HOW TO IMPORT IN phpMyAdmin:
--  1. Go to http://localhost/phpmyadmin
--  2. Click the "SQL" tab
--  3. Copy & paste this entire file
--  4. Click "Go"
--
--  OR via terminal:
--  mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS `property_portal`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `property_portal`;

-- Drop if exists (clean re-run)
DROP TABLE IF EXISTS `property_leads`;

-- ============================================================
--  TABLE: property_leads
--  Stores all inquiries submitted via the landing page
-- ============================================================
CREATE TABLE `property_leads` (
    `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT   COMMENT 'Primary key',
    `name`          VARCHAR(100)    NOT NULL                  COMMENT 'Full name',
    `phone`         VARCHAR(20)     NOT NULL                  COMMENT 'Phone number',
    `email`         VARCHAR(150)    NOT NULL                  COMMENT 'Email address',
    `city`          VARCHAR(100)    NOT NULL                  COMMENT 'Target city',
    `property_type` ENUM('House','Apartment','Plot','Commercial')
                                    NOT NULL                  COMMENT 'Type of property',
    `purpose`       ENUM('Buy','Rent','Sell')
                                    NOT NULL                  COMMENT 'Purpose of inquiry',
    `budget`        BIGINT UNSIGNED DEFAULT NULL              COMMENT 'Budget in PKR',
    `message`       TEXT            DEFAULT NULL              COMMENT 'Additional requirements',
    `ip_address`    VARCHAR(45)     DEFAULT NULL              COMMENT 'Submitter IP address',
    `created_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Submission time',
    `updated_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                    ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_email`   (`email`),
    KEY `idx_city`    (`city`),
    KEY `idx_created` (`created_at`)

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Property inquiry leads from landing page';


-- ============================================================
--  SAMPLE DATA (for demo purposes — remove before production)
-- ============================================================
INSERT INTO `property_leads`
    (name, phone, email, city, property_type, purpose, budget, message, ip_address)
VALUES
    ('Ahmed Khan',   '+92 300 1234567', 'ahmed@example.com',  'Lahore',    'House',      'Buy',  15000000, '5-marla house in DHA Phase 6.',         '127.0.0.1'),
    ('Sara Malik',   '+92 321 9876543', 'sara@example.com',   'Karachi',   'Apartment',  'Rent',    45000, '2-bed apartment near Clifton.',         '127.0.0.1'),
    ('Bilal Akhtar', '+92 333 5556677', 'bilal@example.com',  'Islamabad', 'Commercial', 'Buy',  50000000, 'Office space in Blue Area, ground floor.','127.0.0.1'),
    ('Fatima Raza',  '+92 311 2223344', 'fatima@example.com', 'Lahore',    'Plot',       'Buy',   8000000, '10-marla plot in Bahria Town Phase 4.', '127.0.0.1');