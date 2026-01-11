<?php
// admin/media.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

// Lazy create table check (just in case)
// Handled in save, but let's assume it exists or will be created on first upload.
// To avoid error on first load if table doesn't exist:
try {
    $stmt = $conn->query("SELECT * FROM media_gallery ORDER BY created_at DESC");
    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $media = []; // Table likely doesn't exist yet
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-navy mb-0">Media Gallery</h2>
    <a href="media-form.php" class="btn btn-primary-custom shadow-sm">
        <i class="fas fa-plus me-2"></i> Upload New
    </a>
</div>

<div class="row g-4">
    <?php if (count($media) > 0): ?>
        <?php foreach ($media as $row): ?>
        <div class="col-md-3 col-sm-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="position-relative">
                    <img src="/assets/uploads/gallery/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top object-fit-cover" alt="..." style="height: 200px;">
                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle shadow" 
                            onclick="if(confirm('Delete this image?')) window.location.href='../api/admin/media_delete.php?id=<?php echo $row['id']; ?>'">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="card-body p-2 text-center">
                    <small class="text-muted fw-bold"><?php echo htmlspecialchars($row['title'] ?: 'Untitled'); ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">No media uploaded yet.</h4>
            <p class="text-muted">Upload photos to display them in the public gallery.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
