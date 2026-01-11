<?php
// admin/donations.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

// Filter Logic
$statusParams = [];
$sql = "SELECT d.*, u.name as user_name 
        FROM donations d 
        LEFT JOIN users u ON d.user_id = u.id";

if (isset($_GET['status']) && !empty($_GET['status'])) {
    $sql .= " WHERE d.payment_status = :status";
    $statusParams['status'] = $_GET['status'];
}

$sql .= " ORDER BY d.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($statusParams);
$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate Total
$total_stmt = $conn->query("SELECT SUM(amount) as total FROM donations WHERE payment_status = 'completed'");
$total_raised = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-navy mb-1">Donations Management</h2>
        <p class="text-muted mb-0">Track and manage all incoming contributions.</p>
    </div>
    <div class="bg-white px-4 py-3 rounded-4 shadow-sm border border-success d-flex align-items-center">
        <div class="rounded-circle bg-success-subtle p-3 me-3 text-success">
            <i class="fas fa-wallet fa-lg"></i>
        </div>
        <div>
            <div class="text-muted small text-uppercase fw-bold">Total Collected</div>
            <div class="fw-bold text-success fs-4">₹<?php echo number_format($total_raised); ?></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-premium rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 px-4 border-bottom border-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-navy">Recent Transactions</h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter me-2"></i><?php echo isset($_GET['status']) && $_GET['status'] ? ucfirst($_GET['status']) : 'All Status'; ?>
                </button>
                <ul class="dropdown-menu shadow-sm border-0">
                    <li><a class="dropdown-item" href="?">All Status</a></li>
                    <li><a class="dropdown-item" href="?status=completed">Completed</a></li>
                    <li><a class="dropdown-item" href="?status=pending">Pending</a></li>
                    <li><a class="dropdown-item" href="?status=failed">Failed</a></li>
                </ul>
            </div>
            
            <a href="/api/admin/donations_export.php?status=<?php echo $_GET['status'] ?? ''; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                <i class="fas fa-download me-2"></i>Export
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Donor</th>
                        <th class="py-3">PAN</th>
                        <th class="py-3">Project</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Status</th>
                        <th class="text-end pe-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($donations) > 0): ?>
                        <?php foreach ($donations as $row): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?php echo $row['id']; ?></td>
                            <td>
                                <div class="fw-bold text-navy"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></div>
                                <small class="text-muted"><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm rounded-circle bg-primary-subtle text-primary me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        <?php echo strtoupper(substr($row['donor_name'] ?: ($row['user_name'] ?: 'A'), 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['donor_name'] ?: ($row['user_name'] ?: 'Anonymous')); ?></div>
                                        <small class="text-muted" style="font-size: 0.8rem;"><?php echo htmlspecialchars($row['donor_email']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted small fw-bold"><?php echo $row['pan_number'] ? htmlspecialchars($row['pan_number']) : 'N/A'; ?></span>
                            </td>
                            <td>
                                <?php if($row['project_id']): ?>
                                    <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1 fw-normal">Project #<?php echo $row['project_id']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1 fw-normal">General Fund</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-bold text-navy">₹<?php echo number_format($row['amount']); ?></span>
                            </td>
                            <td>
                                <?php if ($row['payment_status'] == 'completed'): ?>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Success
                                    </span>
                                <?php elseif ($row['payment_status'] == 'pending'): ?>
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                        <i class="fas fa-times-circle me-1"></i> Failed
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px;">
                                        <i class="fas fa-ellipsis-v fa-sm"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg animate__animated animate__fadeIn" style="min-width: 200px;">
                                        <li>
                                            <h6 class="dropdown-header text-uppercase fw-bold text-xs ls-1 text-muted py-2 px-3">Manage</h6>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2 px-3 d-flex align-items-center" href="#" 
                                               onclick='viewDonationDetails(<?php echo json_encode($row); ?>)'>
                                                <div class="icon-square bg-primary-subtle text-primary rounded me-3" style="width: 24px; height: 24px; display: grid; place-items: center;">
                                                    <i class="fas fa-eye fa-xs"></i>
                                                </div>
                                                <span class="small fw-medium">View Details</span>
                                            </a>
                                        </li>
                                        <?php if ($row['payment_status'] == 'completed'): ?>
                                            <li>
                                                <a class="dropdown-item py-2 px-3 d-flex align-items-center" href="../public/receipt-pdf.php?id=<?php echo $row['id']; ?>">
                                                    <div class="icon-square bg-danger-subtle text-danger rounded me-3" style="width: 24px; height: 24px; display: grid; place-items: center;">
                                                        <i class="fas fa-file-pdf fa-xs"></i>
                                                    </div>
                                                    <span class="small fw-medium">PDF Receipt</span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <!-- Optional Audit Log or other actions -->
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="fas fa-box-open fa-3x opacity-25"></i></div>
                                No donations found yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Donation Details Modal -->
<div class="modal fade" id="donationDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="modal-title fw-bold text-navy">Donation Details <span id="modalDonationId" class="text-muted small ms-2"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div id="modalStatusIcon" class="mb-2"></div>
                    <h3 class="fw-bold text-navy mb-0" id="modalAmount"></h3>
                    <div id="modalStatusBadge" class="mt-2"></div>
                </div>

                <div class="bg-light rounded-4 p-3 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Date & Time</span>
                        <span class="fw-bold small text-dark" id="modalDate"></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Project</span>
                        <span class="fw-bold small text-dark" id="modalProject"></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Transaction ID</span>
                        <span class="fw-bold small text-dark font-monospace" id="modalTxnId"></span>
                    </div>
                </div>

                <h6 class="text-uppercase text-muted small fw-bold mb-3 ls-1">Donor Information</h6>
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm rounded-circle bg-primary-subtle text-primary me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark" id="modalDonorName"></div>
                        <div class="small text-muted" id="modalDonorEmail"></div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="avatar-sm rounded-circle bg-light text-muted me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">PAN Number</div>
                        <div class="fw-bold text-dark" id="modalDonorPan"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <a href="#" id="modalReceiptBtn" class="btn btn-primary-custom rounded-pill px-4" target="_blank">
                    <i class="fas fa-download me-2"></i> Receipt
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Admin Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/dashboard.js"></script>
<script>
function viewDonationDetails(data) {
    // Populate Modal
    document.getElementById('modalDonationId').innerText = '#' + data.id;
    document.getElementById('modalAmount').innerText = '₹' + new Intl.NumberFormat('en-IN').format(data.amount);
    
    // Date
    const date = new Date(data.created_at);
    document.getElementById('modalDate').innerText = date.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    
    // Project
    document.getElementById('modalProject').innerText = data.project_id ? 'Project #' + data.project_id : 'General Fund';
    document.getElementById('modalTxnId').innerText = data.transaction_id || 'N/A';
    
    // Donor
    document.getElementById('modalDonorName').innerText = data.donor_name || data.user_name || 'Anonymous';
    document.getElementById('modalDonorEmail').innerText = data.donor_email || 'N/A';
    document.getElementById('modalDonorPan').innerText = data.pan_number || 'Not Provided';

    // Status Styling
    let statusHtml = '';
    let iconHtml = '';
    
    if (data.payment_status === 'completed') {
        statusHtml = '<span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Success</span>';
        iconHtml = '<div class="rounded-circle bg-success-subtle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;"><i class="fas fa-check fa-lg text-success"></i></div>';
        document.getElementById('modalReceiptBtn').href = '../public/receipt-pdf.php?id=' + data.id;
        document.getElementById('modalReceiptBtn').style.display = 'inline-block';
    } else {
        statusHtml = '<span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1">' + data.payment_status + '</span>';
        iconHtml = '<div class="rounded-circle bg-danger-subtle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;"><i class="fas fa-times fa-lg text-danger"></i></div>';
        document.getElementById('modalReceiptBtn').style.display = 'none';
    }
    
    document.getElementById('modalStatusBadge').innerHTML = statusHtml;
    document.getElementById('modalStatusIcon').innerHTML = iconHtml;

    // Show Modal
    const modal = new bootstrap.Modal(document.getElementById('donationDetailsModal'));
    modal.show();
}
</script>
</body>
</html>
