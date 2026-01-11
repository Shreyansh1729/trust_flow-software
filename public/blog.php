<?php
// public/blog.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$db = new Database();
$conn = $db->getConnection();

// Lazy table check for public side to avoid crashes if admin hasn't visited yet
try {
    $stmt = $conn->query("SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC");
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $blogs = [];
}
?>

<!-- Hero Section -->
<section class="hero-section position-relative d-flex align-items-center justify-content-center" style="background-image: url('/assets/img/hero_blog.png'); background-size: cover; background-position: center; height: 50vh; min-height: 350px;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-navy" style="opacity: 0.85; background: linear-gradient(rgba(10, 25, 47, 0.9), rgba(10, 25, 47, 0.7)); z-index: 1;"></div>
    <div class="container position-relative text-center text-white" style="z-index: 2;">
        <h1 class="display-3 fw-bold mb-3 animate__animated animate__fadeInUp" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Stories of Change</h1>
        <p class="lead text-white-50 mx-auto animate__animated animate__fadeInUp animate__delay-1s" style="max-width: 600px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            Latest news, updates, and impact stories from the ground.
        </p>
    </div>
</section>

<!-- Blog Grid -->
<section class="section-padding bg-gray-light">
    <div class="container">
        <div class="row g-4">
            <?php if (count($blogs) > 0): ?>
                <?php foreach ($blogs as $row): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-hover h-100 border-0 shadow-sm">
                            <a href="blog-read.php?slug=<?php echo $row['slug']; ?>" class="text-decoration-none text-dark">
                                <div class="position-relative overflow-hidden">
                                     <img src="<?php echo !empty($row['image']) ? '/assets/uploads/blog/' . $row['image'] : '/assets/img/cause-education.png'; ?>" 
                                          class="card-img-top object-fit-cover" alt="Thumb" style="height: 240px;">
                                     <div class="badge bg-white text-navy position-absolute top-0 start-0 m-3 px-3 py-2 fw-bold shadow-sm">
                                         <?php echo date('M d', strtotime($row['created_at'])); ?>
                                     </div>
                                </div>
                                <div class="card-body p-4">
                                    <h4 class="card-title fw-bold mb-3 hover-orange"><?php echo htmlspecialchars($row['title']); ?></h4>
                                    <p class="card-text text-muted mb-4">
                                        <?php echo htmlspecialchars(substr(strip_tags($row['content']), 0, 120)) . '...'; ?>
                                    </p>
                                    <div class="d-flex align-items-center text-muted small fw-bold text-uppercase letter-spacing-1">
                                        <span class="text-orange me-2">Read More</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">No stories have been published yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
