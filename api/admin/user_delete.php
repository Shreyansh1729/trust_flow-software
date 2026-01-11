<?php
// api/admin/user_delete.php
require_once '../../includes/functions.php';
require_once '../../config/db.php';

checkAdmin();

$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('/admin/users.php', 'Invalid User ID.', 'danger');
}

$db = new Database();
$conn = $db->getConnection();

// Prevent deleting self
if ($id == $_SESSION['user_id']) {
    redirect('/admin/users.php', 'You cannot delete yourself!', 'danger');
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");

if ($stmt->execute([$id])) {
    redirect('/admin/users.php', 'User deleted successfully.', 'success');
} else {
    redirect('/admin/users.php', 'Failed to delete user.', 'danger');
}
?>
