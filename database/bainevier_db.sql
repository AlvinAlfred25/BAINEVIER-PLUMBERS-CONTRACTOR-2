-- =========================================================
-- Bainevier Plumbers & Contractors — Database Schema
-- Import this via phpMyAdmin (XAMPP) before using the site.
-- =========================================================

CREATE DATABASE IF NOT EXISTS bainevier_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE bainevier_db;

-- Sign Up accounts
CREATE TABLE IF NOT EXISTS users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  full_name     VARCHAR(100) NOT NULL,
  email         VARCHAR(150) NOT NULL UNIQUE,
  phone         VARCHAR(20)  NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contact Us / quote-request submissions
CREATE TABLE IF NOT EXISTS contact_messages (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  full_name       VARCHAR(100) NOT NULL,
  phone           VARCHAR(20)  NOT NULL,
  preferred_date  DATE NULL,
  service         VARCHAR(100) NOT NULL,
  message         TEXT NULL,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
