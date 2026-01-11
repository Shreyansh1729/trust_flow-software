<?php
// public/financials.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/db.php';

$db = new Database();
$conn = $db->getConnection();

// Fetch Settings
$settings_stmt = $conn->query("SELECT setting_key, setting_value FROM settings");
$settings = [];
while ($row = $settings_stmt->fetch(PDO::FETCH_ASSOC)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
// Defaults
$legalName = $settings['legal_name'] ?? 'TrustFlow Foundation';
$auditor = $settings['auditor_name'] ?? 'Sharma & Associates';
$regNo = $settings['registration_no'] ?? 'N/A';
$fcra = $settings['fcra_status'] ?? 'Pending';

// Fetch Documents
$reports = $conn->query("SELECT * FROM documents WHERE category = 'report' ORDER BY year DESC, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$legalDocs = $conn->query("SELECT * FROM documents WHERE category = 'legal' ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
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
                    
                    <!-- Trust Badge -->
                    <div class="d-inline-flex align-items-center bg-white bg-opacity-10 rounded-pill px-4 py-2 mb-4 border border-white border-opacity-10">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="text-white small fw-bold text-uppercase ls-1">Officially Registered & Compliant</span>
                    </div>

                    <h1 class="display-3 fw-bold text-white mb-3 tracking-tight">Financial Transparency</h1>
                    <p class="lead text-gray-300 mx-auto mb-5" style="max-width: 700px;">
                        We believe that trust is earned through openness. Access our diverse financial reports, audit summaries, and legal certifications below.
                    </p>

                    <!-- Quick Stats Pills -->
                    <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                        <span class="badge bg-navy-light text-white px-3 py-2 rounded-pill shadow-sm border border-light border-opacity-25">
                            <i class="fas fa-file-invoice-dollar text-orange me-2"></i> 80G Tax Exemption
                        </span>
                        <span class="badge bg-navy-light text-white px-3 py-2 rounded-pill shadow-sm border border-light border-opacity-25">
                            <i class="fas fa-university text-info me-2"></i> 12A Registered
                        </span>
                        <span class="badge bg-navy-light text-white px-3 py-2 rounded-pill shadow-sm border border-light border-opacity-25">
                            <i class="fas fa-search-dollar text-success me-2"></i> Annual Audits
                        </span>
                    </div>

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

<!-- Financial Reports Section -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-navy">Annual Reports</h2>
            <p class="text-muted">Review our yearly performance and financial health.</p>
        </div>

        <?php if (count($reports) > 0): ?>
            <div class="row g-4 justify-content-center">
                <?php foreach ($reports as $doc): ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body p-4 text-center">
                            <div class="mb-4 text-primary bg-primary-subtle rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-file-pdf fa-2x"></i>
                            </div>
                            <h4 class="fw-bold text-navy"><?php echo htmlspecialchars($doc['title']); ?></h4>
                            <p class="text-muted small mb-4">Click below to download the official audit report and impact summary.</p>
                            <a href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="btn btn-outline-primary rounded-pill px-4">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3 opacity-25"></i>
                <p class="text-muted">No annual reports uploaded yet.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Legal Documents Section -->
<section class="section-padding bg-gray-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0">
                <h2 class="fw-bold text-navy mb-4">Legal Compliance</h2>
                <p class="text-muted mb-4 lead">
                    <?php echo htmlspecialchars($legalName); ?> is fully compliant with all government regulations.
                </p>
                <div class="d-flex flex-column gap-3">
                    <?php if (count($legalDocs) > 0): ?>
                        <?php foreach ($legalDocs as $doc): ?>
                        <div class="d-flex align-items-center bg-white p-3 rounded-3 shadow-sm">
                            <i class="fas fa-certificate text-orange fa-2x me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($doc['title']); ?></h6>
                                <p class="mb-0 text-muted small">Official Certificate</p>
                            </div>
                            <a href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="ms-auto text-primary"><i class="fas fa-download"></i></a>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted small">Legal documents will be uploaded shortly.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-6 offset-lg-1">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-5 text-center bg-navy text-white">
                        <i class="fas fa-shield-alt fa-4x mb-4 text-orange"></i>
                        <h3 class="fw-bold mb-3">Commitment to Integrity</h3>
                        <p class="text-white-50 mb-4">
                            We adhere to the highest standards of financial accountability. Our accounts are audited annually by an independent chartered accountant.
                        </p>
                        <div class="row text-start mt-4">
                            <div class="col-6">
                                <small class="text-white-50 text-uppercase fw-bold ls-1">Legal Name</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($legalName); ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-white-50 text-uppercase fw-bold ls-1">Auditor</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($auditor); ?></p>
                            </div>
                            <div class="col-6 mt-3">
                                <small class="text-white-50 text-uppercase fw-bold ls-1">Registration No.</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($regNo); ?></p>
                            </div>
                            <div class="col-6 mt-3">
                                <small class="text-white-50 text-uppercase fw-bold ls-1">FCRA Status</small>
                                <p class="fw-bold"><?php echo htmlspecialchars($fcra); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.bg-navy { background-color: var(--navy-dark); }
.text-orange { color: var(--orange-vibrant); }
.hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
.ls-1 { letter-spacing: 1px; }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
