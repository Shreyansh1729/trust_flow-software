<?php
// admin/team.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

$conn->exec("CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    bio TEXT,
    image VARCHAR(255),
    category ENUM('trustee', 'volunteer') DEFAULT 'trustee',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $conn->query("SELECT * FROM team_members ORDER BY category ASC, created_at DESC");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-navy mb-0">Team Management</h2>
    <a href="team-form.php" class="btn btn-primary-custom rounded-pill shadow-sm px-4">
        <i class="fas fa-plus me-2"></i> Add Member
    </a>
</div>

<div class="card border-0 shadow-premium rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Member</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Category</th>
                        <th class="text-end pe-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($members) > 0): ?>
                        <?php foreach ($members as $m): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <?php if($m['image']): ?>
                                        <img src="<?php echo htmlspecialchars($m['image']); ?>" class="rounded-circle me-3 object-fit-cover shadow-sm" width="40" height="40">
                                    <?php else: ?>
                                        <div class="avatar-sm rounded-circle bg-light text-muted me-3 d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($m['name'], 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="fw-bold text-navy"><?php echo htmlspecialchars($m['name']); ?></div>
                                </div>
                            </td>
                            <td class="text-muted"><?php echo htmlspecialchars($m['role']); ?></td>
                            <td>
                                <?php if($m['category'] == 'trustee'): ?>
                                    <span class="badge bg-purple-subtle text-purple rounded-pill px-3" style="background-color: #f3e8ff; color: #9333ea;">Trustee</span>
                                <?php else: ?>
                                    <span class="badge bg-orange-subtle text-orange rounded-pill px-3">Volunteer</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="team-form.php?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-secondary rounded-start text-dark"><i class="fas fa-edit"></i></a>
                                    <a href="/api/admin/team_delete.php?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-danger rounded-end" onclick="return confirm('Delete this member?')"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">No team members found. Add some!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/dashboard.js"></script>
</body>
</html>
