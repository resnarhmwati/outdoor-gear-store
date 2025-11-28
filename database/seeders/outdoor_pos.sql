-- Outdoor Gear POS Database Setup
-- Run this SQL file to create database and tables

-- Create Database
CREATE DATABASE IF NOT EXISTS outdoor_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE outdoor_pos;

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Customers Table
CREATE TABLE IF NOT EXISTS customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(255) UNIQUE NOT NULL,
    customer_id BIGINT UNSIGNED NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    paid_amount DECIMAL(10, 2) NOT NULL,
    change_amount DECIMAL(10, 2) NOT NULL DEFAULT 0,
    order_date TIMESTAMP NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Sample Categories
INSERT INTO categories (name, description) VALUES
('Tenda & Camping', 'Perlengkapan camping dan tenda outdoor'),
('Tas & Ransel', 'Tas carrier dan daypack untuk hiking'),
('Pakaian Outdoor', 'Jaket, celana, dan pakaian outdoor'),
('Sepatu & Sandal', 'Sepatu hiking dan sandal gunung'),
('Alat Navigasi', 'Kompas, GPS, dan alat navigasi'),
('Peralatan Masak', 'Kompor portable, nesting, dan peralatan masak');

-- Insert Sample Products
INSERT INTO products (category_id, name, description, price, stock) VALUES
(1, 'Tenda Dome 4 Orang', 'Tenda dome kapasitas 4 orang, waterproof', 850000, 15),
(1, 'Sleeping Bag', 'Sleeping bag untuk suhu dingin', 350000, 25),
(1, 'Matras Lipat', 'Matras lipat ringan dan nyaman', 200000, 30),
(2, 'Carrier 60L', 'Tas carrier 60L untuk expedition', 1200000, 10),
(2, 'Daypack 25L', 'Tas daypack 25L untuk hiking', 450000, 20),
(2, 'Dry Bag 20L', 'Dry bag waterproof 20L', 180000, 35),
(3, 'Jaket Windbreaker', 'Jaket windbreaker anti air', 550000, 18),
(3, 'Celana Hiking Quick Dry', 'Celana hiking quick dry', 380000, 22),
(3, 'Kaos Trekking', 'Kaos quick dry untuk trekking', 150000, 40),
(4, 'Sepatu Hiking Mid', 'Sepatu hiking waterproof mid cut', 950000, 12),
(4, 'Sandal Gunung', 'Sandal gunung dengan tali kuat', 280000, 25),
(5, 'Kompas Silva', 'Kompas Silva orienteering', 450000, 15),
(5, 'GPS Garmin', 'GPS Garmin outdoor', 3500000, 5),
(5, 'Headlamp LED', 'Headlamp LED 300 lumens', 250000, 30),
(6, 'Kompor Portable', 'Kompor portable gas canister', 350000, 20),
(6, 'Nesting 3 Pcs', 'Set nesting 3 pieces', 180000, 25),
(6, 'Spork Titanium', 'Sendok garpu titanium', 120000, 40);

-- Insert Sample Customers
INSERT INTO customers (name, email, phone, address) VALUES
('Budi Santoso', 'budi@email.com', '08123456789', 'Jakarta Selatan'),
('Siti Nurhaliza', 'siti@email.com', '08234567890', 'Bandung'),
('Ahmad Wijaya', 'ahmad@email.com', '08345678901', 'Surabaya'),
('Rina Kusuma', 'rina@email.com', '08456789012', 'Yogyakarta'),
('Dedi Prasetyo', 'dedi@email.com', '08567890123', 'Bogor');
