<?php
// api/admin/volunteer_delete.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $db = new Database();
    $conn = $db->getConnection();

    try {
        $stmt = $conn->prepare("DELETE FROM volunteers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        // Redirect back
        header("Location: /admin/volunteers.php?msg=deleted");
    } catch (PDOException $e) {
        header("Location: /admin/volunteers.php?error=failed");
    }
} else {
    header("Location: /admin/volunteers.php");
}
?>
