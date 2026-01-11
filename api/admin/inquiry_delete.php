<?php
// api/admin/inquiry_delete.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $db = new Database();
    $conn = $db->getConnection();

    try {
        $stmt = $conn->prepare("DELETE FROM inquiries WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        // Redirect back with success message
        header("Location: /admin/inquiries.php?msg=deleted");
    } catch (PDOException $e) {
        header("Location: /admin/inquiries.php?error=failed");
    }
} else {
    header("Location: /admin/inquiries.php");
}
?>
