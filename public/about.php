<?php
// public/about.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- About Hero -->
<section class="hero-section" style="background-image: url('/assets/img/hero-home.png'); height: 60vh; min-height: 400px;">
    <div class="container hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="animate__animated animate__fadeInUp">Our Story</h1>
                <p class="lead text-white animate__animated animate__fadeInUp animate__delay-1s">
                    Driven by compassion, guided by transparency.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Trust Story Section -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="position-relative">
                    <img src="/assets/img/about-mission.png" class="img-fluid rounded-4 shadow-premium hover-lift" alt="Our Mission">
                    <div class="position-absolute bottom-0 start-0 bg-white p-4 rounded-3 shadow-lg m-4" style="max-width: 250px;">
                        <h4 class="fw-bold text-navy mb-0">10+ Years</h4>
                        <p class="text-muted mb-0">Of Impactful Service</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <h6 class="text-orange text-uppercase letter-spacing-2 fw-bold mb-3">Who We Are</h6>
                <h2 class="display-5 fw-bold text-navy mb-4">Building a Future Where Everyone Thrives</h2>
                <p class="text-muted fs-5 mb-4">
                    Founded in 2014, TrustFlow began with a simple mission: to bridge the gap between generous hearts and communities in need through absolute transparency.
                </p>
                <p class="text-muted mb-4">
                    We believe that trust is the currency of social change. That's why every project we undertake is documented, every rupee accounted for, and every impact story verified. From education in rural villages to disaster relief in urban centers, we are committed to holistic development.
                </p>
                
                <div class="row g-4 mt-2">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-orange fa-2x me-3"></i>
                            <div>
                                <h5 class="fw-bold text-navy mb-0">100% Transparent</h5>
                                <small class="text-muted">Open financial records</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users text-orange fa-2x me-3"></i>
                            <div>
                                <h5 class="fw-bold text-navy mb-0">Community Led</h5>
                                <small class="text-muted">Locally driven solutions</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trustees Section -->
<section class="section-padding bg-gray-light" id="trustees">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-orange text-uppercase letter-spacing-2 fw-bold">Leadership</h6>
            <h2 class="fw-bold display-5 text-navy">Meet Our Trustees</h2>
            <p class="text-muted fs-5">The visionaries guiding our mission</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            <?php
            // Fetch Trustees
            $db = new Database();
            $conn = $db->getConnection();
            $stmt = $conn->query("SELECT * FROM team_members WHERE category = 'trustee' ORDER BY created_at ASC");
            $trustees = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if (count($trustees) > 0): ?>
                <?php foreach($trustees as $t): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 bg-transparent text-center h-100">
                        <div class="position-relative d-inline-block mx-auto mb-4">
                            <?php if($t['image']): ?>
                                <img src="<?php echo htmlspecialchars($t['image']); ?>" class="rounded-circle shadow-lg object-fit-cover" alt="<?php echo htmlspecialchars($t['name']); ?>" style="width: 180px; height: 180px; border: 4px solid white;">
                            <?php else: ?>
                                <div class="rounded-circle shadow-lg d-flex align-items-center justify-content-center bg-white text-muted fw-bold display-4" style="width: 180px; height: 180px; border: 4px solid white;">
                                    <?php echo strtoupper(substr($t['name'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold text-navy"><?php echo htmlspecialchars($t['name']); ?></h5>
                        <p class="text-orange fw-bold small mb-2"><?php echo htmlspecialchars($t['role']); ?></p>
                        <p class="text-muted small px-2"><?php echo htmlspecialchars($t['bio']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No trustees added yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
