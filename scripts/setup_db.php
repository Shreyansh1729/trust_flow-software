<?php
// scripts/setup_db.php
require_once __DIR__ . '/../config/db.php';

echo "🔌 Connecting to Database...\n";
$db = new Database();
$pdo = $db->getConnection();

$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        phone VARCHAR(20),
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'volunteer', 'donor') DEFAULT 'donor',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "donations" => "CREATE TABLE IF NOT EXISTS donations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        amount DECIMAL(10,2) NOT NULL,
        donor_name VARCHAR(255),
        donor_email VARCHAR(255),
        pan_number VARCHAR(20),
        payment_status VARCHAR(50),
        transaction_id VARCHAR(255),
        project_id INT NULL,
        user_id INT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "testimonials" => "CREATE TABLE IF NOT EXISTS testimonials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        role VARCHAR(255),
        message TEXT,
        image VARCHAR(255),
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "blogs" => "CREATE TABLE IF NOT EXISTS blogs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        content TEXT,
        image VARCHAR(255),
        status ENUM('draft', 'published') DEFAULT 'draft',
        author VARCHAR(100),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )",
    "documents" => "CREATE TABLE IF NOT EXISTS documents (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        category ENUM('legal', 'report') NOT NULL,
        file_path VARCHAR(255) NOT NULL,
        year INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "settings" => "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        key_name VARCHAR(255) UNIQUE,
        value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "inquiries" => "CREATE TABLE IF NOT EXISTS inquiries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        subject VARCHAR(200),
        message TEXT,
        status ENUM('new', 'read', 'replied') DEFAULT 'new',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "media_gallery" => "CREATE TABLE IF NOT EXISTS media_gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        image VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $name => $sql) {
    try {
        $pdo->exec($sql);
        echo "✅ Table '$name' checked/created.\n";
    } catch (PDOException $e) {
        echo "❌ Error dealing with '$name': " . $e->getMessage() . "\n";
    }
}

echo "🎉 Database Setup Complete!\n";
?>
