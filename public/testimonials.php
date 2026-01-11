<?php
// public/testimonials.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT * FROM testimonials WHERE status = 'approved' ORDER BY created_at DESC");
$testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);

$msg = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!-- Hero -->
<section class="hero-section position-relative d-flex align-items-center justify-content-center" style="background-image: url('/assets/img/hero_testimonials.png'); background-size: cover; background-position: center; height: 50vh; min-height: 350px;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-navy" style="opacity: 0.85; background: linear-gradient(rgba(10, 25, 47, 0.9), rgba(10, 25, 47, 0.7)); z-index: 1;"></div>
    <div class="container position-relative text-center text-white" style="z-index: 2;">
        <h1 class="display-3 fw-bold mb-3 animate__animated animate__fadeInUp" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Community Stories</h1>
        <p class="lead text-white-50 mx-auto animate__animated animate__fadeInUp animate__delay-1s mb-4" style="max-width: 600px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            Hear from the people who make our mission possible.
        </p>
        <button class="btn btn-orange btn-lg fw-bold shadow-lg animate__animated animate__fadeInUp animate__delay-2s" data-bs-toggle="modal" data-bs-target="#submitModal">
            <i class="fas fa-pen-nib me-2"></i> Share Your Story
        </button>
    </div>
</section>

<section class="section-padding bg-white">
    <div class="container">
        <?php if($msg === 'submitted'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> Thank you! Your story has been submitted for review.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4 masonry-grid">
            <?php foreach ($testimonials as $t): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 p-4 rounded-4">
                    <div class="mb-4 text-orange">
                        <i class="fas fa-quote-left fa-2x opacity-25"></i>
                    </div>
                    <p class="text-muted lh-lg mb-4 fst-italic">"<?php echo htmlspecialchars($t['message']); ?>"</p>
                    
                    <div class="d-flex align-items-center mt-auto">
                        <?php if($t['image']): ?>
                            <img src="/assets/uploads/testimonials/<?php echo htmlspecialchars($t['image']); ?>" class="rounded-circle me-3 object-fit-cover" width="50" height="50">
                        <?php else: ?>
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <span class="fw-bold text-navy"><?php echo substr($t['name'], 0, 1); ?></span>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h6 class="fw-bold text-navy mb-0"><?php echo htmlspecialchars($t['name']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($t['role']); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Submission Modal -->
<div class="modal fade" id="submitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-navy text-white rounded-top-4 p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-heart text-orange me-2"></i> Share Your Impact</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="../api/public/testimonial_submit.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Your Name</label>
                        <input type="text" class="form-control" name="name" required placeholder="John Doe">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role (Optional)</label>
                        <input type="text" class="form-control" name="role" placeholder="e.g. Volunteer, Donor">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Your Story</label>
                        <textarea class="form-control" name="message" rows="4" required placeholder="How has this organization impacted you?"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Photo (Optional)</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <div class="form-text small">Your photo adds a personal touch!</div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-orange fw-bold py-2">Submit for Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
