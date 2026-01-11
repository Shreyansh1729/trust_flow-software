<?php
// admin/blogs.php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/../config/db.php';

checkAdmin();

$db = new Database();
$conn = $db->getConnection();

// Table creation handled by scripts/setup_db.php

// Fetch Blogs
$sql = "SELECT * FROM blogs ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-navy mb-0">Blog & News Management</h2>
    <a href="blog-form.php" class="btn btn-primary-custom shadow-sm">
        <i class="fas fa-pen-nib me-2"></i> Write New Article
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr class="bg-gray-light">
                        <th class="ps-4" width="200">Featured Image</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($blogs) > 0): ?>
                        <?php foreach ($blogs as $row): ?>
                        <tr>
                            <td class="ps-4">
                                <?php if(!empty($row['image'])): ?>
                                    <img src="/assets/uploads/blog/<?php echo $row['image']; ?>" class="rounded-3 object-fit-cover shadow-sm" width="120" height="80" alt="Thumb">
                                <?php else: ?>
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted border" style="width:120px; height:80px;"> No Image </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-navy mb-1"><?php echo htmlspecialchars($row['title']); ?></div>
                                <small class="text-muted">/<?php echo htmlspecialchars($row['slug']); ?></small>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'published'): ?>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3">Published</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small">
                                <i class="far fa-calendar me-1"></i> <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                            </td>
                            <td class="text-end pe-4">
                                <a href="blog-form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                                <a href="../api/admin/blog_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this post?');"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No articles found. Start writing!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; // Using relative path if needed, or stick to structure ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
