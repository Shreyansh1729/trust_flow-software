<?php
// api/admin/project_delete.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

if (isset($_GET['id'])) {
    $db = new Database();
    $conn = $db->getConnection();

    try {
        // Optional: Delete image file from server
        // $stmt = $conn->prepare("SELECT image FROM projects WHERE id = ?");
        // ... (implementation to unlink file)

        $stmt = $conn->prepare("DELETE FROM projects WHERE id = :id");
        $stmt->execute(['id' => $_GET['id']]);
        header("Location: /admin/projects.php?msg=deleted");
    } catch (PDOException $e) {
        header("Location: /admin/projects.php?error=failed");
    }
} else {
    header("Location: /admin/projects.php");
}
?>
