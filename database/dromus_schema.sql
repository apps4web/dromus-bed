-- Dromus Bed & Boetiek - MySQL schema
-- Creates content tables for texts, photos, reviews and reservations.
-- Compatible with MySQL 8+

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- Optional:
-- CREATE DATABASE IF NOT EXISTS dromus_bed CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE dromus_bed;

CREATE TABLE IF NOT EXISTS site_texts (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  section_key VARCHAR(100) NOT NULL,
  field_key VARCHAR(100) NOT NULL,
  locale VARCHAR(10) NOT NULL DEFAULT 'nl',
  content TEXT NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_site_texts_section_field_locale (section_key, field_key, locale),
  KEY idx_site_texts_section (section_key),
  KEY idx_site_texts_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS site_photos (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  section_key VARCHAR(100) NOT NULL,
  title VARCHAR(255) NULL,
  alt_text VARCHAR(255) NULL,
  image_url VARCHAR(2048) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_site_photos_section (section_key),
  KEY idx_site_photos_active_sort (is_active, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS site_reviews (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  guest_name VARCHAR(120) NOT NULL,
  initials VARCHAR(6) NULL,
  location VARCHAR(120) NULL,
  rating TINYINT UNSIGNED NOT NULL,
  review_text TEXT NOT NULL,
  review_date DATE NULL,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_site_reviews_published_sort (is_published, sort_order),
  KEY idx_site_reviews_rating (rating),
  CONSTRAINT chk_site_reviews_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reservations (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) NULL,
  checkin_date DATE NOT NULL,
  checkout_date DATE NOT NULL,
  guests TINYINT UNSIGNED NOT NULL,
  message TEXT NULL,
  status ENUM('new','confirmed','cancelled','completed') NOT NULL DEFAULT 'new',
  source ENUM('website','phone','email','walkin') NOT NULL DEFAULT 'website',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_reservations_dates (checkin_date, checkout_date),
  KEY idx_reservations_status (status),
  KEY idx_reservations_email (email),
  CONSTRAINT chk_reservations_dates CHECK (checkout_date > checkin_date),
  CONSTRAINT chk_reservations_guests CHECK (guests BETWEEN 1 AND 10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','editor') NOT NULL DEFAULT 'editor',
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  last_login_at TIMESTAMP NULL DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email),
  KEY idx_users_role (role),
  KEY idx_users_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
