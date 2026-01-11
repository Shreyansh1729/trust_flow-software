<?php
// api/admin/subscribers_export.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->query("SELECT email, created_at FROM subscribers ORDER BY created_at DESC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="subscribers_list_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Email', 'Subscribed Date']);

foreach ($rows as $row) {
    fputcsv($output, [$row['email'], $row['created_at']]);
}

fclose($output);
exit;
?>
