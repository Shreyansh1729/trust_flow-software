<?php
// api/admin/testimonial_status.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

$id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;

if ($id && in_array($status, ['approved', 'rejected'])) {
    $db = new Database();
    $conn = $db->getConnection();
    
    // If rejected, we might want to just delete logic or mark as rejected. 
    // Plan said "Update status to 'rejected' or Delete". Let's update status to keep record, or delete. 
    // User requested "Approve or have some action button". 
    // Let's stick to status updates for safety, maybe soft delete.
    // If status is rejected, let's essentially hide it but keep it in DB for now. 
    
    $stmt = $conn->prepare("UPDATE testimonials SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $id]);
}

header("Location: /admin/testimonials.php?msg=" . $status);
?>
