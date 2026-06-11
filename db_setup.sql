-- Memory Box 2.0 Database Schema Setup Script
-- Import this into MySQL to configure the database structure.

CREATE DATABASE IF NOT EXISTS memoryboxDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE memoryboxDB;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    bio TEXT NULL,
    profile_photo VARCHAR(255) DEFAULT 'assets/images/default-avatar.svg',
    favorite_category VARCHAR(50) DEFAULT 'General',
    reset_token VARCHAR(255) NULL,
    reset_expires DATETIME NULL,
    is_verified TINYINT DEFAULT 0,
    verification_token VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- 2. Memories Table
CREATE TABLE IF NOT EXISTS memories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    event_date DATE NOT NULL,
    mood VARCHAR(50) NOT NULL,
    privacy ENUM('private', 'public', 'friends') DEFAULT 'private',
    is_archived TINYINT DEFAULT 0,
    is_favorite TINYINT DEFAULT 0,
    cover_image VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_category (user_id, category),
    INDEX idx_event_date (event_date)
) ENGINE=InnoDB;

-- 3. Memory Photos Table (Support multiple photo uploads)
CREATE TABLE IF NOT EXISTS memory_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    memory_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (memory_id) REFERENCES memories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Memory Tags Table
CREATE TABLE IF NOT EXISTS memory_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    memory_id INT NOT NULL,
    tag_name VARCHAR(50) NOT NULL,
    FOREIGN KEY (memory_id) REFERENCES memories(id) ON DELETE CASCADE,
    UNIQUE KEY uq_memory_tag (memory_id, tag_name),
    INDEX idx_tag (tag_name)
) ENGINE=InnoDB;

-- 5. Future Letters Table (Time Capsules)
CREATE TABLE IF NOT EXISTS future_letters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    recipient VARCHAR(100) NOT NULL,
    unlock_date DATE NOT NULL,
    is_opened TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_unlock (user_id, unlock_date)
) ENGINE=InnoDB;

-- 6. Reviews Table
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
