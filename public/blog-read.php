<?php
// public/blog-read.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$slug = $_GET['slug'] ?? '';
$id = $_GET['id'] ?? '';

$db = new Database();
$conn = $db->getConnection();

$post = null;
if ($slug) {
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE slug = :slug AND status = 'published'");
    $stmt->execute(['slug' => $slug]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif ($id) {
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE id = :id AND status = 'published'");
    $stmt->execute(['id' => $id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$post) {
    echo "<div class='container py-5 text-center'><h3>Article not found</h3><a href='blog.php' class='btn btn-primary-custom mt-3'>Back to News</a></div>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}
?>

<!-- Minimalist Read Layout -->
<article class="bg-white">
    <!-- Header Image -->
    <div class="w-100 position-relative" style="height: 60vh; min-height: 400px;">
        <img src="<?php echo !empty($post['image']) ? '/assets/uploads/blog/' . $post['image'] : '/assets/img/cause-education.png'; ?>" 
             class="w-100 h-100 object-fit-cover" 
             alt="<?php echo htmlspecialchars($post['title']); ?>">
        <div class="position-absolute bottom-0 start-0 w-100 bg-gradient-to-t p-5 d-flex align-items-end" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); height: 50%;">
            <div class="container" style="max-width: 800px;">
                <h1 class="display-4 fw-bold text-white mb-3"><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="d-flex align-items-center text-white-50 gap-4">
                    <span><i class="fas fa-user-circle me-2"></i> <?php echo htmlspecialchars($post['author']); ?></span>
                    <span><i class="far fa-calendar me-2"></i> <?php echo date('F d, Y', strtotime($post['created_at'])); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Body -->
    <div class="container py-5" style="max-width: 800px;">
        <div class="blog-content fs-5 text-dark" style="line-height: 1.8;">
            <?php echo nl2br(htmlspecialchars($post['content'])); // Basic output, potentially unsafe if rich editor used later. Ideally use a purifier. ?>
        </div>

        <!-- Share Buttons -->
        <div class="border-top mt-5 pt-5 d-flex justify-content-between align-items-center">
            <span class="fw-bold text-muted text-uppercase small">Share this story</span>
            <div class="d-flex gap-3">
                <a href="#" class="btn btn-light rounded-circle"><i class="fab fa-facebook-f text-primary"></i></a>
                <a href="#" class="btn btn-light rounded-circle"><i class="fab fa-twitter text-info"></i></a>
                <a href="#" class="btn btn-light rounded-circle"><i class="fab fa-whatsapp text-success"></i></a>
                <a href="#" class="btn btn-light rounded-circle"><i class="fas fa-link text-muted"></i></a>
            </div>
        </div>
        
        <div class="text-center mt-5">
            <a href="blog.php" class="btn btn-outline-custom rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> More Stories
            </a>
        </div>
    </div>
    
</article>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
