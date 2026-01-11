-- Database Schema for TrustFlow
-- 
-- CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci

DROP DATABASE IF EXISTS trust_flow_db;
CREATE DATABASE trust_flow_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE trust_flow_db;

-- 1. Users Table (Admins, Donors, etc.)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'donor', 'volunteer') DEFAULT 'donor',
    phone VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Projects / Causes Table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    content LONGTEXT,
    goal_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    raised_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    status ENUM('active', 'completed', 'paused') DEFAULT 'active',
    image_url VARCHAR(255),
    start_date DATE,
    end_date DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3. Donations Table
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL, -- Nullable for guest donations
    project_id INT NULL, -- Nullable for general fund
    amount DECIMAL(15, 2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'INR',
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payment_gateway VARCHAR(50), -- e.g., 'razorpay', 'stripe'
    payment_id VARCHAR(100), -- Transaction ID from gateway
    is_anonymous TINYINT(1) DEFAULT 0,
    pan_number VARCHAR(20), -- For tax exemption
    donor_name VARCHAR(100), -- Captured if guest
    donor_email VARCHAR(150), -- Captured if guest
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- 4. Expenses Table
CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    amount DECIMAL(15, 2) NOT NULL,
    expense_date DATE NOT NULL,
    category VARCHAR(50),
    proof_document VARCHAR(255), -- URL/Path to receipt
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INT NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- 5. Blogs / News Table
CREATE TABLE blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT,
    author_id INT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    published_at DATETIME,
    featured_image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 6. Events Table
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    event_date DATETIME,
    location VARCHAR(255),
    capacity INT DEFAULT 0,
    image_url VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 7. Volunteers Table
CREATE TABLE volunteers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    skills TEXT,
    availability TEXT,
    message TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    application_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 8. Inquiries / Contact Messages
CREATE TABLE inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 9. Audit Logs Table
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 10. Settings Table (Global Config)
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    description VARCHAR(255),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Default Super Admin
-- Password 'admin123' hashed with BCrypt
INSERT INTO users (name, email, password, role, created_at)
VALUES (
    'Super Admin', 
    'admin@trustflow.org', 
    '$2y$12$jyYeRKpPoaa0PFhZSaiJXO/1eTCSLe.W2NuJ/19qQ8XD9B2oZC9KC', 
    'admin', 
    NOW()
);

-- Default Settings (Example)
INSERT INTO settings (setting_key, setting_value, description) VALUES 
('site_name', 'TrustFlow', 'Name of the application'),
('contact_email', 'info@trustflow.org', 'Primary contact email'),
('currency_symbol', '₹', 'Default currency symbol');
