<?php
// api/admin/media_delete.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

$id = $_GET['id'] ?? null;
if ($id) {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Optional: Delete physical file (advanced step, skipping for now to be safe with shared assets)
    
    $stmt = $conn->prepare("DELETE FROM media_gallery WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header("Location: /admin/media.php?msg=deleted");
?>
