<?php
// api/admin/testimonial_delete.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

$id = $_GET['id'] ?? null;
if ($id) {
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header("Location: /admin/testimonials.php?msg=deleted");
?>
