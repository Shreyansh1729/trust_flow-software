<?php
// public/team.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/db.php';
?>

<!-- Hero Section -->
<section class="position-relative py-5 d-flex align-items-center justify-content-center" style="min-height: 500px; background: radial-gradient(circle at top right, #1e293b, #0f172a); overflow: hidden;">
    <!-- Background Image with Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('/assets/img/hero_impact.png') no-repeat center center/cover; opacity: 0.15; z-index: 0;"></div>
    
    <!-- Animated Orbs -->
    <div class="position-absolute top-0 end-0 bg-primary opacity-25 rounded-circle blur-3xl animate__animated animate__pulse animate__infinite" style="width: 400px; height: 400px; filter: blur(80px); animation-duration: 8s;"></div>
    <div class="position-absolute bottom-0 start-0 bg-orange opacity-10 rounded-circle blur-3xl" style="width: 300px; height: 300px; filter: blur(60px);"></div>

    <div class="container position-relative z-2">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="glass-card p-5 text-center rounded-5 shadow-lg border border-white border-opacity-10 animate__animated animate__zoomIn">
                    
                    <!-- Badge -->
                    <div class="d-inline-flex align-items-center bg-white bg-opacity-10 rounded-pill px-4 py-2 mb-4 border border-white border-opacity-10">
                        <i class="fas fa-users text-info me-2"></i>
                        <span class="text-white small fw-bold text-uppercase ls-1">Leadership & Community</span>
                    </div>

                    <h1 class="display-3 fw-bold text-white mb-3 tracking-tight">Meet The Changemakers</h1>
                    <p class="lead text-gray-300 mx-auto mb-5" style="max-width: 700px;">
                        The dedicated hearts and minds driving TrustFlow's mission forward. Our diverse team of trustees and volunteers is united by a passion for service.
                    </p>

                    <!-- Quick Stats Pills -->
                    <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                        <span class="badge bg-navy-light text-white px-3 py-2 rounded-pill shadow-sm border border-light border-opacity-25">
                            <i class="fas fa-user-tie text-orange me-2"></i> Dedicated Trustees
                        </span>
                        <span class="badge bg-navy-light text-white px-3 py-2 rounded-pill shadow-sm border border-light border-opacity-25">
                            <i class="fas fa-hands-helping text-success me-2"></i> Active Volunteers
                        </span>
                        <span class="badge bg-navy-light text-white px-3 py-2 rounded-pill shadow-sm border border-light border-opacity-25">
                            <i class="fas fa-globe text-info me-2"></i> Global Impact
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}
.blur-3xl { filter: blur(64px); }
.text-orange { color: var(--orange-vibrant) !important; }
.bg-navy-light { background-color: rgba(30, 41, 59, 0.8); }
.tracking-tight { letter-spacing: -1px; }
.ls-1 { letter-spacing: 1px; }
</style>

<?php
// Fetch Team Members
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->query("SELECT * FROM team_members ORDER BY created_at ASC");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

$trustees = array_filter($members, fn($m) => $m['category'] == 'trustee');
$volunteers = array_filter($members, fn($m) => $m['category'] == 'volunteer');
?>

<!-- Board Members -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-orange text-uppercase letter-spacing-2 fw-bold">Leadership</h6>
            <h2 class="fw-bold text-navy">Board of Trustees</h2>
        </div>
        
        <div class="row g-4 justify-content-center">
            <?php foreach($trustees as $t): ?>
            <div class="col-md-4 text-center">
                <?php if($t['image']): ?>
                    <img src="<?php echo htmlspecialchars($t['image']); ?>" class="rounded-circle shadow-lg mb-4 object-fit-cover" width="200" height="200" style="border: 5px solid var(--gray-100);">
                <?php else: ?>
                    <div class="rounded-circle shadow-lg mb-4 mx-auto d-flex align-items-center justify-content-center bg-light text-muted fw-bold" style="width: 200px; height: 200px; font-size: 3rem; border: 5px solid var(--gray-100);">
                        <?php echo strtoupper(substr($t['name'], 0, 1)); ?>
                    </div>
                <?php endif; ?>
                <h4 class="fw-bold text-navy"><?php echo htmlspecialchars($t['name']); ?></h4>
                <p class="text-muted mb-2"><?php echo htmlspecialchars($t['role']); ?></p>
                <p class="small text-muted px-4"><?php echo htmlspecialchars($t['bio']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Volunteers -->
<section class="section-padding bg-gray-light">
    <div class="container">
        <div class="text-center mb-5">
             <h6 class="text-orange text-uppercase letter-spacing-2 fw-bold">Our Strength</h6>
            <h2 class="fw-bold text-navy">Dedicated Volunteers</h2>
        </div>
        
        <div class="row text-center justify-content-center">
            <?php foreach($volunteers as $v): ?>
            <div class="col-6 col-md-2 mb-4">
                <div class="bg-white p-2 rounded-circle shadow-sm d-inline-block">
                    <?php if($v['image']): ?>
                        <img src="<?php echo htmlspecialchars($v['image']); ?>" class="rounded-circle object-fit-cover" width="100" height="100">
                    <?php else: ?>
                         <div class="rounded-circle bg-light text-muted d-flex align-items-center justify-content-center fw-bold" style="width: 100px; height: 100px; font-size: 1.5rem;">
                            <?php echo strtoupper(substr($v['name'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h6 class="fw-bold mt-2"><?php echo htmlspecialchars($v['name']); ?></h6>
            </div>
            <?php endforeach; ?>
            
            <div class="col-6 col-md-2 mb-4">
                <a href="volunteer.php" class="text-decoration-none">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-orange text-white mx-auto shadow-sm hover-lift cursor-pointer" style="width:100px; height:100px;">
                        <span class="fw-bold">Join Us</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
