<?php
// admin/media-form.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();
?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-navy mb-0">Upload Media</h2>
            <a href="media.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
        </div>

        <div class="card border-0 shadow-premium rounded-4">
            <div class="card-body p-5">
                <form action="../api/admin/media_save.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Image Title (Optional)</label>
                        <input type="text" class="form-control py-3" name="title" placeholder="Event name or description">
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label fw-bold">Select Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-custom py-3 fw-bold">
                            <i class="fas fa-cloud-upload-alt me-2"></i> Upload Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
