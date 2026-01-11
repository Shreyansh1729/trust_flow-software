<?php
// admin/blog-form.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

$post = null;
$is_edit = false;

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($post) $is_edit = true;
}
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-navy mb-0"><?php echo $is_edit ? 'Edit Article' : 'Compose New Article'; ?></h2>
            <a href="blogs.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
        </div>

        <form action="../api/admin/blog_save.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $post['id'] ?? ''; ?>">
            
            <div class="row g-4">
                <!-- Main Content (Left) -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Article Title</label>
                                <input type="text" class="form-control form-control-lg py-3 fw-bold text-navy" name="title" placeholder="Enter a catchy title..." value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="mb-0">
                                <label class="form-label fw-bold">Content</label>
                                <!-- Basic Textarea to avoid CKEditor complexity for now, but configured tall -->
                                <textarea class="form-control" name="content" rows="15" placeholder="Start writing your story here..." style="font-size: 1.1rem; line-height: 1.6;"><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>
                                <div class="form-text mt-2"><i class="fab fa-markdown"></i> Markdown or HTML allows.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Settings (Right) -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom fw-bold py-3">Publishing</div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Status</label>
                                <select class="form-select" name="status">
                                    <option value="draft" <?php echo ($post['status'] ?? '') == 'draft' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="published" <?php echo ($post['status'] ?? '') == 'published' ? 'selected' : ''; ?>>Published</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Author</label>
                                <input type="text" class="form-control" name="author" value="<?php echo htmlspecialchars($post['author'] ?? $_SESSION['user_name']); ?>">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary-custom py-3 fw-bold">
                                    <i class="fas fa-save me-2"></i> Save Article
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                         <div class="card-header bg-white border-bottom fw-bold py-3">Featured Image</div>
                         <div class="card-body p-4">
                            <input type="file" class="form-control mb-3" name="image" accept="image/*">
                            <?php if($is_edit && !empty($post['image'])): ?>
                                <img src="/assets/uploads/blog/<?php echo $post['image']; ?>" class="img-fluid rounded-3 shadow-sm" alt="Preview">
                            <?php endif; ?>
                         </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
