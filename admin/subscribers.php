<?php
// admin/subscribers.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

// Fetch subscribers
$subscribers = [];
try {
    $stmt = $conn->query("SELECT * FROM subscribers ORDER BY created_at DESC");
    $subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Table might not exist yet if no one subscribed
    $subscribers = [];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-navy mb-0">Newsletter Subscribers</h2>
    <div class="d-flex gap-2">
        <button onclick="copyEmails()" class="btn btn-primary-custom rounded-pill shadow-sm px-4">
            <i class="fas fa-copy me-2"></i> Copy All
        </button>
        <a href="/api/admin/subscribers_export.php" class="btn btn-outline-primary rounded-pill shadow-sm px-4">
            <i class="fas fa-file-csv me-2"></i> Export CSV
        </a>
    </div>
</div>

<div class="card border-0 shadow-premium rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">#</th>
                        <th class="py-3">Email Address</th>
                        <th class="py-3">Subscribed Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($subscribers) > 0): ?>
                        <?php foreach ($subscribers as $index => $sub): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted"><?php echo $index + 1; ?></td>
                            <td class="fw-bold text-navy">
                                <?php echo htmlspecialchars($sub['email']); ?>
                            </td>
                            <td class="text-muted small">
                                <?php echo date('M d, Y h:i A', strtotime($sub['created_at'])); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="fas fa-envelope-open-text fa-3x opacity-25"></i></div>
                                <p>No subscribers yet.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div> <!-- End Container -->
</div> <!-- End Main Content -->

<!-- Copy Emails Script -->
<script>
function copyEmails() {
    const emails = <?php echo json_encode(array_column($subscribers, 'email')); ?>;
    if (emails.length === 0) {
        alert('No emails to copy.');
        return;
    }
    const emailString = emails.join(','); // or '\n' or '; '
    navigator.clipboard.writeText(emailString).then(() => {
        alert('Copied ' + emails.length + ' emails to clipboard!');
    }).catch(err => {
        alert('Failed to copy text: ' + err);
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/dashboard.js"></script>
</body>
</html>
