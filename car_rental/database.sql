-- ============================================================
-- Car Rental System â€” Database Setup
-- Database: car_rental
-- Tables: users, cars, bookings
-- Import this file via phpMyAdmin to set up the database
-- ============================================================

DROP DATABASE IF EXISTS `car_rental`;
CREATE DATABASE `car_rental` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `car_rental`;

-- ============================================================
-- USERS TABLE
-- ============================================================
CREATE TABLE `users` (
    `id`         INT PRIMARY KEY AUTO_INCREMENT,
    `full_name`  VARCHAR(100) NOT NULL,
    `email`      VARCHAR(150) NOT NULL UNIQUE,
    `password`   VARCHAR(255) NOT NULL,
    `phone`      VARCHAR(20) DEFAULT NULL,
    `role`       ENUM('user','admin') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- CARS TABLE
-- ============================================================
CREATE TABLE `cars` (
    `id`            INT PRIMARY KEY AUTO_INCREMENT,
    `name`          VARCHAR(100) NOT NULL,
    `brand`         VARCHAR(100) NOT NULL,
    `type`          ENUM('Sedan','SUV','Hatchback','Luxury','Convertible','Van') NOT NULL,
    `seats`         INT NOT NULL DEFAULT 5,
    `price_per_day` DECIMAL(10,2) NOT NULL,
    `image`         VARCHAR(255) DEFAULT 'placeholder.png',
    `description`   TEXT,
    `fuel_type`     ENUM('Petrol','Diesel','Electric','Hybrid') DEFAULT 'Petrol',
    `transmission`  ENUM('Manual','Automatic') DEFAULT 'Automatic',
    `available`     TINYINT(1) NOT NULL DEFAULT 1,
    `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- BOOKINGS TABLE
-- ============================================================
CREATE TABLE `bookings` (
    `id`          INT PRIMARY KEY AUTO_INCREMENT,
    `user_id`     INT NOT NULL,
    `car_id`      INT NOT NULL,
    `start_date`  DATE NOT NULL,
    `end_date`    DATE NOT NULL,
    `total_days`  INT NOT NULL,
    `total_price` DECIMAL(10,2) NOT NULL,
    `status`      ENUM('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'confirmed',
    `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`car_id`)  REFERENCES `cars`(`id`)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA â€” USERS
-- password for all demo users: password123
-- Generated with: password_hash('password123', PASSWORD_DEFAULT)
-- ============================================================
INSERT INTO `users` (`full_name`, `email`, `password`, `phone`, `role`) VALUES
('Admin User',  'admin@carrental.com', '$2y$10$koXQaMQ0Da0dSY2i0E2h5eEwl2GkEpvKwPToz5kLRKuk.6i7.7vK6', '+91-9000000001', 'admin'),
('Demo User',   'demo@carrental.com',  '$2y$10$koXQaMQ0Da0dSY2i0E2h5eEwl2GkEpvKwPToz5kLRKuk.6i7.7vK6', '+91-9000000002', 'user');

-- ============================================================
-- SEED DATA â€” CARS
-- ============================================================
INSERT INTO `cars` (`name`, `brand`, `type`, `seats`, `price_per_day`, `image`, `description`, `fuel_type`, `transmission`, `available`) VALUES
('Fortuner',     'Toyota',   'SUV',        7, 2500.00, 'car_suv.png',        'Premium 7-seater SUV perfect for family trips and long drives. Powerful engine with excellent road presence and top safety ratings.',      'Diesel',  'Automatic', 1),
('City',         'Honda',    'Sedan',       5, 1500.00, 'car_sedan.png',      'Elegant and fuel-efficient sedan with premium features. Ideal for city commutes, business travel, and weekend getaways.',                  'Petrol',  'Automatic', 1),
('Swift',        'Maruti',   'Hatchback',   5,  900.00, 'car_hatchback.png',  'Sporty and nimble hatchback perfect for city driving. Great fuel economy, easy to park, and fun to drive.',                              'Petrol',  'Manual',    1),
('S-Class',      'Mercedes', 'Luxury',      4, 8000.00, 'car_luxury.png',     'The pinnacle of luxury motoring. Ultra-premium executive sedan with top-tier comfort, cutting-edge technology and unmatched elegance.',     'Petrol',  'Automatic', 1),
('Mustang GT',   'Ford',     'Convertible', 4, 5000.00, 'car_convertible.png','Iconic American muscle convertible. Feel the wind in your hair with raw power, stunning looks, and head-turning style.',                  'Petrol',  'Automatic', 1),
('Innova Crysta','Toyota',   'Van',         8, 2000.00, 'car_van.png',        'Spacious and comfortable 8-seater MPV. Perfect for group travel, family outings, corporate trips, and airport transfers.',                  'Diesel',  'Manual',    1);

-- ============================================================
-- NOTES:
-- Default admin: admin@carrental.com  / password123
-- Default user:  demo@carrental.com   / password123
-- ============================================================

