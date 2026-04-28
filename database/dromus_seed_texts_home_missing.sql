-- Dromus Bed & Boetiek - missing home page text seeds
-- Adds keys used in templates/Home/index.php that are absent from
-- dromus_seed.sql and dromus_seed_texts_home_extra.sql.
-- Run after database/dromus_schema.sql.

SET NAMES utf8mb4;
SET time_zone = '+00:00';

START TRANSACTION;

INSERT INTO site_texts (section_key, field_key, locale, content, is_active)
VALUES
  ('about', 'boutique_url', 'nl', 'https://bunnibow.nl', 1)
ON DUPLICATE KEY UPDATE
  content = VALUES(content),
  is_active = VALUES(is_active),
  updated_at = CURRENT_TIMESTAMP;

COMMIT;
