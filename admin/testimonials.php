<?php
// admin/testimonials.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

// Lazy creating table just in case save script hasn't run yet
$conn->exec("CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(255) DEFAULT 'Supporter',
    message TEXT,
    image VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$activeTab = $_GET['tab'] ?? 'approved';
$statusCtx = ($activeTab === 'pending') ? 'pending' : 'approved';

$stmt = $conn->prepare("SELECT * FROM testimonials WHERE status = :status ORDER BY created_at DESC");
$stmt->execute(['status' => $statusCtx]);
$testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count Pending
$pendingCount = $conn->query("SELECT COUNT(*) FROM testimonials WHERE status = 'pending'")->fetchColumn();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-navy mb-0">Testimonials</h2>
    <a href="testimonial-form.php" class="btn btn-primary-custom shadow-sm">
        <i class="fas fa-plus me-2"></i> Add New
    </a>
</div>

<!-- Tabs -->
<ul class="nav nav-pills mb-4">
  <li class="nav-item">
    <a class="nav-link <?php echo $activeTab === 'approved' ? 'active bg-navy' : 'text-muted'; ?>" href="?tab=approved">
        <i class="fas fa-check-circle me-1"></i> Approved
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $activeTab === 'pending' ? 'active bg-orange' : 'text-muted'; ?>" href="?tab=pending">
        <i class="fas fa-clock me-1"></i> Pending Review
        <?php if($pendingCount > 0): ?>
            <span class="badge bg-danger ms-1 rounded-pill"><?php echo $pendingCount; ?></span>
        <?php endif; ?>
    </a>
  </li>
</ul>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Person</th>
                    <th>Role</th>
                    <th>Message</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($testimonials) > 0): ?>
                    <?php foreach ($testimonials as $row): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <?php if($row['image']): ?>
                                    <img src="/assets/uploads/testimonials/<?php echo htmlspecialchars($row['image']); ?>" class="rounded-circle me-3 object-fit-cover" width="40" height="40">
                                <?php else: ?>
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></span>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['role']); ?></span></td>
                        <td><small class="text-muted text-truncate d-inline-block" style="max-width: 300px;"><?php echo htmlspecialchars($row['message']); ?></small></td>
                        <td class="text-end pe-4">
                            
                            <?php if($activeTab === 'pending'): ?>
                                <a href="../api/admin/testimonial_status.php?id=<?php echo $row['id']; ?>&status=approved" class="btn btn-sm btn-success me-1" title="Approve">
                                    <i class="fas fa-check"></i>
                                </a>
                                <a href="../api/admin/testimonial_status.php?id=<?php echo $row['id']; ?>&status=rejected" class="btn btn-sm btn-outline-danger me-1" title="Reject">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php else: ?>
                                <a href="testimonial-form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" 
                                        onclick="if(confirm('Delete this testimonial?')) window.location.href='../api/admin/testimonial_delete.php?id=<?php echo $row['id']; ?>'">
                                    <i class="fas fa-trash"></i>
                                </button>
                            <?php endif; ?>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">No <?php echo $activeTab; ?> testimonials found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
