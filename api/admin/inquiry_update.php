<?php
// api/admin/inquiry_update.php
require_once '../../includes/functions.php';
require_once '../../config/db.php';

header('Content-Type: application/json');

if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;
$status = $input['status'] ?? null;

if (!$id || !$status) {
    echo json_encode(['success' => false, 'message' => 'Invalid Request']);
    exit;
}

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("UPDATE inquiries SET status = :status WHERE id = :id");
$result = $stmt->execute(['status' => $status, 'id' => $id]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database Error']);
}
?>
