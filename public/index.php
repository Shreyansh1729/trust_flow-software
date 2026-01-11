<?php
// public/index.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section" style="background-image: url('/assets/img/hero-home.png');">
    <div class="container hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="animate__animated animate__fadeInUp">
                    Empowering Communities,<br>Transforming Lives
                </h1>
                <p class="animate__animated animate__fadeInUp animate__delay-1s">
                    Join us in creating lasting change through transparent, impactful giving.
                </p>
                <div class="d-flex gap-3 justify-content-center mt-4 animate__animated animate__fadeInUp animate__delay-2s">
                    <a href="/public/donate.php" class="btn btn-primary-custom">
                        Start Giving <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="/public/about.php" class="btn btn-outline-custom">
                        Our Story <i class="fas fa-book-open ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Impact Stats Section -->
<section class="section-padding bg-white" id="impact-stats">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="p-4">
                    <div class="mb-3">
                        <i class="fas fa-hand-holding-usd fa-4x text-orange"></i>
                    </div>
                    <h2 class="display-4 fw-bold counter text-navy" data-target="1500000">0</h2>
                    <p class="text-uppercase fw-semibold text-muted letter-spacing-2">Funds Raised (₹)</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="mb-3">
                        <i class="fas fa-users fa-4x text-orange"></i>
                    </div>
                    <h2 class="display-4 fw-bold counter text-navy" data-target="10000">0</h2>
                    <p class="text-uppercase fw-semibold text-muted letter-spacing-2">Lives Touched</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <div class="mb-3">
                        <i class="fas fa-user-friends fa-4x text-orange"></i>
                    </div>
                    <h2 class="display-4 fw-bold counter text-navy" data-target="500">0</h2>
                    <p class="text-uppercase fw-semibold text-muted letter-spacing-2">Dedicated Volunteers</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Causes -->
<section class="section-padding bg-gray-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-5 text-navy">Featured Causes</h2>
            <p class="text-muted fs-5">Support the projects that need your help right now</p>
        </div>
        
        <div class="row g-4">
            <?php
            // Fetch 3 Active Projects
            $db = new Database();
            $conn = $db->getConnection();
            $stmt = $conn->query("SELECT * FROM projects WHERE status = 'active' LIMIT 3");
            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if (count($projects) > 0): ?>
                <?php foreach($projects as $row): ?>
                    <?php 
                        $percent = ($row['goal_amount'] > 0) ? round(($row['raised_amount'] / $row['goal_amount']) * 100) : 0;
                        
                        // Image Path Logic
                        $imgSrc = '/assets/img/hero-home.png'; // Default
                        if (!empty($row['image'])) {
                            if (strpos($row['image'], '/') !== false) {
                                // Full path or uploaded path already set
                                $imgSrc = $row['image'];
                            } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/img/' . $row['image'])) {
                                // Static asset
                                $imgSrc = '/assets/img/' . $row['image'];
                            } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/assets/uploads/projects/' . $row['image'])) {
                                // Uploaded asset
                                $imgSrc = '/assets/uploads/projects/' . $row['image'];
                            } else {
                                // Fallback assumption
                                $imgSrc = '/assets/img/' . $row['image'];
                            }
                        }
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-premium rounded-4 overflow-hidden position-relative card-hover-lift">
                            <div class="card-img-wrapper position-relative">
                                <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="card-img-top object-fit-cover" alt="<?php echo htmlspecialchars($row['title']); ?>" style="height: 260px; transition: transform 0.5s ease;">
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-overlay"></div>
                                <div class="badge bg-orange px-3 py-2 position-absolute top-0 end-0 m-3 rounded-pill shadow-sm" style="font-weight: 600; font-size: 0.8rem; letter-spacing: 0.5px;">FEATURED</div>
                            </div>
                            
                            <div class="card-body p-4 bg-white position-relative">
                                <h4 class="card-title fw-bold text-navy mb-3"><?php echo htmlspecialchars($row['title']); ?></h4>
                                <p class="card-text text-muted mb-4 small" style="line-height: 1.6;"><?php echo htmlspecialchars(substr($row['description'], 0, 110)) . '...'; ?></p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2 small fw-bold">
                                    <span class="text-orange">Raised: <span class="text-dark">₹<?php echo number_format($row['raised_amount']); ?></span></span>
                                    <span class="text-muted">Goal: ₹<?php echo number_format($row['goal_amount']); ?></span>
                                </div>
                                
                                <div class="progress rounded-pill bg-light mb-4" style="height: 10px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);">
                                    <div class="progress-bar rounded-pill bg-gradient-orange" role="progressbar" style="width: <?php echo $percent; ?>%;" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <a href="/public/donate.php?project=<?php echo $row['id']; ?>" class="btn btn-primary-custom w-100 py-3 rounded-pill shadow-sm fw-bold">
                                    Donate Now <i class="fas fa-heart ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted lead">No active projects at the moment. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
/* Card Enhancements */
.card-hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card-hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
}
.card-hover-lift:hover .card-img-top {
    transform: scale(1.05);
}
.bg-gradient-overlay {
    background: linear-gradient(to bottom, rgba(0,0,0,0) 60%, rgba(0,0,0,0.6) 100%);
    pointer-events: none;
}
.bg-gradient-orange {
    background: linear-gradient(90deg, var(--orange-vibrant) 0%, #fb923c 100%);
}
</style>

<!-- Call to Action -->
<section class="section-padding position-relative" style="background-image: url('/assets/img/cause-education.png'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(15, 23, 42, 0.85);"></div> <!-- Dark Overlay -->
    <div class="container text-center text-white position-relative z-1">
        <h2 class="display-4 fw-bold mb-4 text-white">Be the Change You Want to See</h2>
        <p class="lead mb-5 text-gray-200" style="max-width: 700px; margin: 0 auto;">Join our community of over 500 dedicated volunteers making a difference every single day. Your time and skills can transform lives.</p>
        <a href="/public/volunteer.php" class="btn btn-primary-custom btn-lg px-5 py-3 shadow-glow">
            Become a Volunteer <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
