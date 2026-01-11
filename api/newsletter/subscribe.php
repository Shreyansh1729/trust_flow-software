<?php
// api/newsletter/subscribe.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = cleanInput($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    // Lazy create table
    $conn->exec("CREATE TABLE IF NOT EXISTS subscribers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(150) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    try {
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (:email)");
        $stmt->execute(['email' => $email]);
        echo json_encode(['success' => true, 'message' => 'Thank you for subscribing!']);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate entry
            echo json_encode(['success' => true, 'message' => 'You are already subscribed.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid method.']);
}
?>
