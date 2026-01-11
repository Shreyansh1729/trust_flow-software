<?php
// api/admin/team_delete.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

if (isset($_GET['id'])) {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Ensure table exists
    $conn->exec("CREATE TABLE IF NOT EXISTS team_members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        role VARCHAR(100) NOT NULL,
        bio TEXT,
        image VARCHAR(255),
        category ENUM('trustee', 'volunteer') DEFAULT 'trustee',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $conn->prepare("DELETE FROM team_members WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: /admin/team.php?msg=deleted");
?>
