<?php
// admin/team-form.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$id = $_GET['id'] ?? null;
$member = null;

if ($id) {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare("SELECT * FROM team_members WHERE id = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-navy mb-0"><?php echo $member ? 'Edit Member' : 'Add Team Member'; ?></h2>
    <a href="team.php" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-premium rounded-4">
            <div class="card-body p-4 p-md-5">
                <form action="/api/admin/team_save.php" method="POST" enctype="multipart/form-data">
                    <?php if ($member): ?>
                        <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                    <?php endif; ?>

                    <div class="mb-4 text-center">
                        <label class="form-label fw-bold text-muted small text-uppercase d-block text-start mb-3">Profile Photo</label>
                        <div class="d-inline-block position-relative">
                            <?php if ($member && $member['image']): ?>
                                <img src="<?php echo htmlspecialchars($member['image']); ?>" class="rounded-circle shadow-sm border border-3 border-light" width="120" height="120" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow-sm border border-3 border-light" style="width: 120px; height: 120px;">
                                    <i class="fas fa-camera fa-2x text-muted opacity-50"></i>
                                </div>
                            <?php endif; ?>
                            <label for="image" class="position-absolute bottom-0 end-0 bg-white shadow-sm rounded-circle p-2 cursor-pointer border border-light" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-pen text-primary small"></i>
                                <input type="file" name="image" id="image" class="d-none" accept="image/*">
                            </label>
                        </div>
                        <div class="form-text mt-2">Recommended: Square image (500x500px)</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-navy small text-uppercase">Full Name</label>
                            <input type="text" name="name" class="form-control bg-light border-0 py-2" required value="<?php echo htmlspecialchars($member['name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                             <label class="form-label fw-bold text-navy small text-uppercase">Role / Title</label>
                            <input type="text" name="role" class="form-control bg-light border-0 py-2" required value="<?php echo htmlspecialchars($member['role'] ?? ''); ?>" placeholder="e.g. Chairman, Volunteer">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-navy small text-uppercase">Category</label>
                        <select name="category" class="form-select bg-light border-0 py-2">
                            <option value="trustee" <?php echo ($member && $member['category'] == 'trustee') ? 'selected' : ''; ?>>Board of Trustee</option>
                            <option value="volunteer" <?php echo ($member && $member['category'] == 'volunteer') ? 'selected' : ''; ?>>Volunteer</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-navy small text-uppercase">Short Bio (Optional)</label>
                        <textarea name="bio" class="form-control bg-light border-0" rows="4" placeholder="Brief description..."><?php echo htmlspecialchars($member['bio'] ?? ''); ?></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-custom rounded-pill py-3 fw-bold shadow-sm">
                            <?php echo $member ? 'Update Member' : 'Add Member'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/dashboard.js"></script>
</body>
</html>
