-- Dromus Bed & Boetiek - starter seed data
-- Use after running database/dromus_schema.sql

SET NAMES utf8mb4;
SET time_zone = '+00:00';

START TRANSACTION;

-- -----------------------------------------------------------------------------
-- SITE TEXTS (idempotent via unique key on section_key + field_key + locale)
-- -----------------------------------------------------------------------------
INSERT INTO site_texts (section_key, field_key, locale, content, is_active)
VALUES
  ('brand', 'name', 'nl', 'Dromus Bed & Boetiek', 1),
  ('brand', 'tagline', 'nl', 'Uw thuis weg van huis', 1),

  ('hero', 'eyebrow', 'nl', 'Welkom bij', 1),
  ('hero', 'title', 'nl', 'Dromus', 1),
  ('hero', 'subtitle', 'nl', 'Bed & Boetiek', 1),
  ('hero', 'description', 'nl', 'Geniet van een unieke verblijfservaring in ons stijlvol ingerichte gastenkamer, midden in het hart van de stad.', 1),
  ('hero', 'cta_label', 'nl', 'Ontdek de kamer', 1),

  ('room', 'eyebrow', 'nl', 'Uw verblijf', 1),
  ('room', 'title', 'nl', 'De Gastenkamer', 1),
  ('room', 'quote', 'nl', '"Een thuis weg van huis"', 1),
  ('room', 'description_1', 'nl', 'Onze ruime, lichtrijke gastenkamer combineert hedendaags comfort met een warme, gezellige sfeer. Gelegen op de bovenverdieping van onze boetiek, biedt de kamer een unieke mix van rust en stadsbeleving.', 1),
  ('room', 'description_2', 'nl', 'Ontwaak met een heerlijk ontbijt, verken de lokale winkeltjes vlak om de hoek, of ontspan gewoon in uw eigen priveoase.', 1),
  ('room', 'price_prefix', 'nl', 'Vanaf', 1),
  ('room', 'price_line', 'nl', 'EUR 170 / nacht', 1),
  ('room', 'price_note', 'nl', 'Inclusief belastingen', 1),
  ('room', 'price_cta_label', 'nl', 'Reserveer nu', 1),

  ('reviews', 'eyebrow', 'nl', 'Wat gasten zeggen', 1),
  ('reviews', 'title', 'nl', 'Reviews', 1),
  ('reviews', 'summary', 'nl', '4.9 / 5 - Gebaseerd op 48 beoordelingen', 1),

  ('location', 'eyebrow', 'nl', 'Hoe vindt je ons', 1),
  ('location', 'title', 'nl', 'Locatie', 1),
  ('location', 'address', 'nl', 'Sint Domusstraat 8, 4301 CP Zierikzee, Nederland', 1),

  ('reservation', 'eyebrow', 'nl', 'Klaar om te verblijven?', 1),
  ('reservation', 'title', 'nl', 'Reserveer uw verblijf', 1),
  ('reservation', 'intro', 'nl', 'Vul het formulier in en wij nemen binnen 24 uur contact met u op om uw reservering te bevestigen.', 1),
  ('reservation', 'submit_label', 'nl', 'Verzend aanvraag', 1),

  ('footer', 'copyright', 'nl', '© 2026 Dromus Bed & Boetiek. Alle rechten voorbehouden.', 1),
  ('footer', 'contact_email', 'nl', 'info@dromuszierikzee.nl', 1),
  ('footer', 'contact_phone', 'nl', '+31 (0)6 24207480', 1)
ON DUPLICATE KEY UPDATE
  content = VALUES(content),
  is_active = VALUES(is_active),
  updated_at = CURRENT_TIMESTAMP;

-- -----------------------------------------------------------------------------
-- PHOTOS
-- -----------------------------------------------------------------------------
DELETE FROM site_photos
WHERE section_key IN ('branding', 'home_slider', 'room_main', 'room_gallery');

INSERT INTO site_photos (section_key, title, alt_text, image_url, sort_order, is_active)
VALUES
  ('branding', 'Dromus logo', 'DROMUS Bed & Boetiek logo', 'img/dromus-logo.jpg', 1, 1),

  ('home_slider', 'Hero slide 1', 'Slaapkamer sfeerbeeld', 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1600&q=80', 1, 1),
  ('home_slider', 'Hero slide 2', 'Kamer interieur', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=1600&q=80', 2, 1),
  ('home_slider', 'Hero slide 3', 'Hotelkamer detail', 'https://images.unsplash.com/photo-1540518614846-7eded433c457?w=1600&q=80', 3, 1),
  ('home_slider', 'Hero slide 4', 'Zithoek en lichtinval', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=1600&q=80', 4, 1),

  ('room_main', 'Gastenkamer overzicht', 'Gastenkamer overzicht', 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=900&q=80', 1, 1),

  ('room_gallery', 'Slaapkamer detail', 'Slaapkamer detail', 'https://images.unsplash.com/photo-1540518614846-7eded433c457?w=700&q=80', 1, 1),
  ('room_gallery', 'Badkamer', 'Badkamer', 'https://images.unsplash.com/photo-1507652313519-d4e9174996dd?w=700&q=80', 2, 1),
  ('room_gallery', 'Zithoek', 'Zithoek', 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=700&q=80', 3, 1);

-- -----------------------------------------------------------------------------
-- REVIEWS
-- -----------------------------------------------------------------------------
DELETE FROM site_reviews;

INSERT INTO site_reviews (guest_name, initials, location, rating, review_text, review_date, is_published, sort_order)
VALUES
  ('Sophie V.', 'SV', 'Gent, België', 5, 'Een absolute pareltje! De kamer was prachtig ingericht, het ontbijt heerlijk en de gastvrouw uiterst vriendelijk. We komen zeker terug.', '2025-05-14', 1, 1),
  ('Marc T.', 'MT', 'Amsterdam, NL', 5, 'Rust, comfort en stijl in één. Het gevoel alsof je te gast bent bij vrienden. De ligging is perfect en de boetiek beneden is ook een bezoek waard!', '2025-06-09', 1, 2),
  ('Eva L.', 'EL', 'Brussel, België', 5, 'Dromus heeft alles wat je zoekt: een warme ontvangst, een sfeervol interieur en een uitmuntende ligging. Ik raad het iedereen aan!', '2025-07-01', 1, 3),
  ('Pieter D.', 'PD', 'Antwerpen, België', 5, 'Ongelooflijk rustige en sfeervolle kamer. Het ontbijt was vers en smakelijk. De eigenares is zo vriendelijk. Een 10 op 10!', '2025-07-22', 1, 4),
  ('Laura M.', 'LM', 'Parijs, FR', 4, 'Fantastisch verblijf in een unieke locatie. De kamer straalt karakter uit en het bed is heerlijk comfortabel. Zeker een aanrader voor een romantisch weekend.', '2025-08-05', 1, 5),
  ('Robin K.', 'RK', 'Utrecht, NL', 5, 'De perfecte uitvalsbasis voor een stedentrip. Schoon, rustig, stijlvol en het ontbijt is iets om van te dromen. Wij waren meteen verliefd op Dromus!', '2025-08-19', 1, 6);

-- -----------------------------------------------------------------------------
-- CMS USERS
-- Replace password hash before first production use.
-- Example hash generation: php -r "echo password_hash('ChangeMe123!', PASSWORD_DEFAULT), PHP_EOL;"
-- -----------------------------------------------------------------------------
INSERT INTO users (full_name, email, password_hash, role, status, last_login_at)
VALUES
  ('Dromus Admin', 'admin@dromuszierikzee.nl', '$2y$10$g/8f8/VvtGdT.cSUQdOcOe/GZlIGDsGQ3QNfJYANTEE7Ga9ZKHTs2', 'admin', 'active', NULL)
ON DUPLICATE KEY UPDATE
  full_name = VALUES(full_name),
  password_hash = VALUES(password_hash),
  role = VALUES(role),
  status = VALUES(status),
  updated_at = CURRENT_TIMESTAMP;

-- -----------------------------------------------------------------------------
-- SAMPLE RESERVATIONS (optional demo rows)
-- -----------------------------------------------------------------------------
INSERT INTO reservations (full_name, email, phone, checkin_date, checkout_date, guests, message, status, source)
VALUES
  ('Niels Vermeer', 'niels@example.com', '+31 6 12345678', '2026-05-16', '2026-05-18', 2, 'Aankomst rond 17:00, graag late check-in indien mogelijk.', 'new', 'website'),
  ('Sanne de Vries', 'sanne@example.com', '+31 6 87654321', '2026-06-03', '2026-06-05', 1, 'Zakelijk bezoek, graag stille kamer.', 'confirmed', 'email');

COMMIT;
