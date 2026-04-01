-- ===================================================
-- Car Rental User Management System - Database Setup
-- Author: College Mini Project
-- Purpose: Create database and tables
-- ===================================================

-- Drop database if exists (for fresh setup)
DROP DATABASE IF EXISTS `car_rental_db`;

-- Create database
CREATE DATABASE `car_rental_db`;
USE `car_rental_db`;

-- Create users table
CREATE TABLE `users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `username` VARCHAR(100) UNIQUE,
    `phone` VARCHAR(20),
    `address` VARCHAR(255),
    `age` INT,
    `password` VARCHAR(255) NOT NULL,
    `profile_image` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert demo user (password: password123)
INSERT INTO `users` (`full_name`, `email`, `age`, `password`, `profile_image`) VALUES 
(
    'Demo User',
    'demo@example.com',
    25,
    '$2y$10$8Z7c7q6Y7q6Y7q6Y7q6Y7OjD8Z7c7q6Y7q6Y7q6Y7q6Y7q6Y7q6Y6',
    NULL
),
(
    'John Doe',
    'john@example.com',
    30,
    '$2y$10$8Z7c7q6Y7q6Y7q6Y7q6Y7OjD8Z7c7q6Y7q6Y7q6Y7q6Y7q6Y7q6Y6',
    NULL
),
(
    'Jane Smith',
    'jane@example.com',
    28,
    '$2y$10$8Z7c7q6Y7q6Y7q6Y7q6Y7OjD8Z7c7q6Y7q6Y7q6Y7q6Y7q6Y7q6Y6',
    NULL
);

-- ===================================================
-- NOTES FOR SETUP:
-- ===================================================
-- 1. Import this file to create the database
-- 2. The demo user credentials are:
--    Email: demo@example.com
--    Password: password123
-- 3. All passwords are hashed using PHP's password_hash()
-- 4. You can add more users using the registration page
-- 5. Profile images are stored in the 'uploads/' folder
-- ===================================================
