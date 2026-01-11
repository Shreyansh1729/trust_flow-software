<?php
// donor-panel/index.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

$user_id = $_SESSION['user_id'];
$db = new Database();
$conn = $db->getConnection();

// 1. Fetch User Stats
// Total Donated
$stmt = $conn->prepare("SELECT SUM(amount) as total FROM donations WHERE user_id = :uid AND payment_status = 'completed'");
$stmt->execute(['uid' => $user_id]);
$total_donated = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Donation Count
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM donations WHERE user_id = :uid AND payment_status = 'completed'");
$stmt->execute(['uid' => $user_id]);
$donation_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;

// 2. Fetch Last 3 Donations
$stmt = $conn->prepare("SELECT * FROM donations WHERE user_id = :uid ORDER BY created_at DESC LIMIT 3");
$stmt->execute(['uid' => $user_id]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row align-items-center mb-5">
    <div class="col-md-8">
        <h1 class="display-5 fw-bold text-navy">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p class="text-muted lead">Thank you for being part of our journey. Your support creates real impact.</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="/public/donate.php" class="btn btn-primary-custom btn-lg shadow-lg">
            Donate Again <i class="fas fa-heart ms-2"></i>
        </a>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Impact Card -->
    <div class="col-md-6 text-white">
        <div class="p-5 rounded-4 shadow-premium position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--navy-dark), var(--navy-medium));">
            <div class="position-relative z-1">
                <h5 class="text-white-50 text-uppercase letter-spacing-2 mb-3">Your Lifetime Impact</h5>
                <h2 class="display-3 fw-bold mb-2">₹<?php echo number_format($total_donated); ?></h2>
                <p class="mb-0 text-white-50"><?php echo $donation_count; ?> successful donations</p>
            </div>
            <!-- Decorative Icon -->
            <i class="fas fa-hand-holding-heart position-absolute" style="font-size: 15rem; color: rgba(255,255,255,0.05); top: -20%; right: -10%;"></i>
        </div>
    </div>

    <!-- Quick Actions / Feature Project -->
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-navy mb-4">Why your support matters</h5>
            <div class="d-flex align-items-start mb-3">
                <div class="bg-orange-light rounded p-2 me-3">
                    <i class="fas fa-check-circle text-orange"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">100% Transparency</h6>
                    <p class="text-muted small">Access financial reports and see exactly where your money goes.</p>
                </div>
            </div>
            <div class="d-flex align-items-start mb-3">
                 <div class="bg-green-light rounded p-2 me-3">
                    <i class="fas fa-receipt text-success"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Tax Benefits</h6>
                    <p class="text-muted small">All donations are eligible for tax deductions under 80G.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent History -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom-0 py-3 rounded-top-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-navy">Recent Contributions</h5>
        <a href="history.php" class="text-orange text-decoration-none fw-bold small">View Full History</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Date</th>
                        <th>Project</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($history) > 0): ?>
                        <?php foreach ($history as $donation): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($donation['created_at'])); ?></td>
                            <td class="fw-medium"><?php echo htmlspecialchars($donation['project_id'] ? "Project #".$donation['project_id'] : "General Fund"); ?></td>
                            <td class="fw-bold">₹<?php echo number_format($donation['amount']); ?></td>
                            <td><span class="badge bg-success-subtle text-success px-3 rounded-pill">Success</span></td>
                            <td>
                                <a href="../public/receipt-print.php?id=<?php echo $donation['id']; ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Download Receipt">
                                    <i class="fas fa-file-invoice"></i> Download
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-heart-broken fa-3x text-muted mb-3 opacity-25"></i>
                                <p class="text-muted">No donations yet. Start your journey today!</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div> <!-- End Container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
