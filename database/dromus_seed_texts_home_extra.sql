-- Dromus Bed & Boetiek - additional home page text seeds
-- Run after database/dromus_schema.sql (and optionally after database/dromus_seed.sql)

SET NAMES utf8mb4;
SET time_zone = '+00:00';

START TRANSACTION;

INSERT INTO site_texts (section_key, field_key, locale, content, is_active)
VALUES
  ('about', 'eyebrow', 'nl', 'Over ons', 1),
  ('about', 'title', 'nl', 'Bed en boetiek in een', 1),
  ('about', 'intro', 'nl', 'Bij Dromus combineren we de rust van een kleinschalig bed & breakfast met de creativiteit van een boetiek vol handgemaakte items. U overnacht in een warme, ruime en stijlvolle kamer en ontdekt unieke producten die met zorg en vakmanschap gemaakt zijn.', 1),
  ('about', 'description_1', 'nl', 'Deze mix maakt een verblijf bij ons anders dan anders: persoonlijk, lokaal en inspirerend. Van een zeer luxe overnachting tot een boetiek waar elk stuk een eigen verhaal heeft.', 1),
  ('about', 'description_2', 'nl', 'Zin om de collectie te ontdekken? Bezoek onze boetiekwebsite en bekijk de handgemaakte selectie online.', 1),
  ('about', 'boutique_cta_label', 'nl', 'Naar de boetiek', 1),

  ('location', 'map_title', 'nl', 'Locatie Dromus Bed & Boetiek', 1),

  ('reservation', 'form_loading', 'nl', 'Formulier laden...', 1),
  ('reservation', 'form_load_error', 'nl', 'Kon het formulier niet laden.', 1),

  ('footer', 'logo_alt', 'nl', 'Dromus logo', 1),
  ('footer', 'brand_main', 'nl', 'Dromus', 1),
  ('footer', 'brand_sub', 'nl', 'Bed & Boetiek', 1),
  ('footer', 'address_short', 'nl', 'Sint Domusstraat 8, 4301 CP Zierikzee', 1),
  ('footer', 'contact_phone_uri', 'nl', '+31624207480', 1)
ON DUPLICATE KEY UPDATE
  content = VALUES(content),
  is_active = VALUES(is_active),
  updated_at = CURRENT_TIMESTAMP;

COMMIT;
