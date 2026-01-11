<?php
// admin/testimonial-form.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$id = $_GET['id'] ?? null;
$testimonial = null;

if ($id) {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $testimonial = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-navy mb-0"><?php echo $id ? 'Edit' : 'Add'; ?> Testimonial</h2>
            <a href="testimonials.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card border-0 shadow-premium rounded-4">
            <div class="card-body p-5">
                <form action="../api/admin/testimonial_save.php" method="POST" enctype="multipart/form-data">
                    <?php if ($id): ?>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <?php endif; ?>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" class="form-control py-3" name="name" 
                               value="<?php echo htmlspecialchars($testimonial['name'] ?? ''); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Role / Designation</label>
                        <input type="text" class="form-control py-3" name="role" 
                               value="<?php echo htmlspecialchars($testimonial['role'] ?? 'Supporter'); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Message</label>
                        <textarea class="form-control" name="message" rows="5" required><?php echo htmlspecialchars($testimonial['message'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label fw-bold">Photo (Optional)</label>
                        <?php if(!empty($testimonial['image'])): ?>
                            <div class="mb-2">
                                <img src="/assets/uploads/testimonials/<?php echo htmlspecialchars($testimonial['image']); ?>" width="100" class="rounded-circle">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-custom py-3 fw-bold">
                            <i class="fas fa-save me-2"></i> Save Testimonial
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
