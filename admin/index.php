<?php
// admin/index.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

$db = new Database();
$conn = $db->getConnection();

// --- 1. Fetch Real Stats ---

// Total Donations (Completed)
$stmt = $conn->query("SELECT SUM(amount) as total FROM donations WHERE payment_status = 'completed'");
$total_donations = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Active Projects
$stmt = $conn->query("SELECT COUNT(*) as count FROM projects WHERE status = 'active'"); 
$total_projects = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

// Pending Projects
$stmt = $conn->query("SELECT COUNT(*) as count FROM projects WHERE status = 'pending'"); 
$pending_projects = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

// Total Users (Excluding Admins)
$stmt = $conn->query("SELECT COUNT(*) as count FROM users WHERE role != 'admin'");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

// Pending Inquiries (If table exists, schema mentioned it)
$total_inquiries = 0;
// Check if table exists to avoid crash if not yet created via migration
try {
    $stmt = $conn->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'new'");
    $total_inquiries = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
} catch (PDOException $e) {
    // Table might not exist yet
}

// --- 2. Fetch Recent Activity (Last 5 Donations) ---
$recent_stmt = $conn->query("
    SELECT d.*, u.name as donor_name 
    FROM donations d 
    LEFT JOIN users u ON d.user_id = u.id 
    ORDER BY d.created_at DESC 
    LIMIT 5
");
$recent_donations = $recent_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row g-4 mb-4">
    <!-- Stat Card: Donations -->
    <div class="col-md-6 col-lg">
        <div class="stat-card primary h-100">
            <div class="stat-icon bg-orange-light">
                <i class="fas fa-rupee-sign"></i>
            </div>
            <h3 class="stat-value">₹<?php echo number_format($total_donations); ?></h3>
            <span class="stat-label">Total Donations</span>
        </div>
    </div>

    <!-- Stat Card: Active Projects -->
    <div class="col-md-6 col-lg">
        <div class="stat-card success h-100">
            <div class="stat-icon bg-green-light">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h3 class="stat-value"><?php echo $total_projects; ?></h3>
            <span class="stat-label">Active Projects</span>
        </div>
    </div>

    <!-- Stat Card: Pending Projects -->
    <div class="col-md-6 col-lg">
        <div class="stat-card warning h-100">
            <div class="stat-icon bg-yellow-light">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <h3 class="stat-value"><?php echo $pending_projects; ?></h3>
            <span class="stat-label">Pending Projects</span>
        </div>
    </div>

    <!-- Stat Card: Users -->
    <div class="col-md-6 col-lg">
        <div class="stat-card info h-100">
            <div class="stat-icon bg-blue-light">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="stat-value"><?php echo $total_users; ?></h3>
            <span class="stat-label">Registered Donors</span>
        </div>
    </div>

    <!-- Stat Card: Inquiries -->
    <div class="col-md-6 col-lg">
        <div class="stat-card danger h-100">
            <div class="stat-icon bg-red-light">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h3 class="stat-value"><?php echo $total_inquiries; ?></h3>
            <span class="stat-label">Pending Inquiries</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 py-3 rounded-top-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-navy">Recent Donations</h5>
                    <a href="donations.php" class="btn btn-sm btn-outline-custom">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Donor</th>
                                <th>Amount</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recent_donations) > 0): ?>
                                <?php foreach ($recent_donations as $donation): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-gray-100 rounded-circle d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px;">
                                                <i class="fas fa-user text-muted small"></i>
                                            </div>
                                            <span class="fw-medium">
                                                <?php echo htmlspecialchars($donation['donor_name'] ?: 'Anonymous'); ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="fw-bold">₹<?php echo number_format($donation['amount']); ?></td>
                                    <td><?php echo htmlspecialchars($donation['project_id'] ?? 'General Fund'); ?></td>
                                    <td>
                                        <?php if ($donation['payment_status'] == 'completed'): ?>
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3">Completed</span>
                                        <?php elseif ($donation['payment_status'] == 'pending'): ?>
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Failed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?php echo date('M d, Y', strtotime($donation['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No recent donations found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div> <!-- End Container -->
</div> <!-- End Main Content -->

<!-- Admin Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/dashboard.js"></script>
</body>
</html>
