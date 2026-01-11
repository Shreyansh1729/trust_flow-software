<?php
// api/admin/donations_export.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

// Build Query based on filters (same logic as display)
$where = [];
$params = [];

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $where[] = "d.payment_status = :status";
    $params['status'] = $_GET['status'];
}

// Default constraint or base query
$sql = "SELECT d.id, d.created_at, 
               COALESCE(d.donor_name, u.name, 'Anonymous') as donor,
               d.donor_email, 
               d.amount, 
               d.payment_status,
               d.transaction_id
        FROM donations d 
        LEFT JOIN users u ON d.user_id = u.id";

if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY d.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="donations_export_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Date', 'Donor Name', 'Email', 'Amount (INR)', 'Status', 'Transaction ID']);

foreach ($rows as $row) {
    fputcsv($output, [
        $row['id'], 
        $row['created_at'], 
        $row['donor'], 
        $row['donor_email'], 
        $row['amount'], 
        ucfirst($row['payment_status']), 
        $row['transaction_id']
    ]);
}

fclose($output);
exit;
?>
