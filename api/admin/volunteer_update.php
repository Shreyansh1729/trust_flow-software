<?php
// api/admin/volunteer_update.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin(); // Ensure only admins can access

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $id = $input['id'] ?? null;
    $status = $input['status'] ?? null;

    if ($id && $status && in_array($status, ['pending', 'approved', 'rejected'])) {
        $db = new Database();
        $conn = $db->getConnection();

        try {
            $stmt = $conn->prepare("UPDATE volunteers SET status = :status WHERE id = :id");
            $stmt->execute(['status' => $status, 'id' => $id]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
