<?php
// public/impact.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$db = new Database();
$conn = $db->getConnection();
?>

<!-- Hero -->
<section class="hero-section position-relative d-flex align-items-center justify-content-center" style="background-image: url('/assets/img/hero_impact.png'); background-size: cover; background-position: center; height: 60vh; min-height: 400px;">
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-navy" style="opacity: 0.85; background: linear-gradient(rgba(10, 25, 47, 0.9), rgba(10, 25, 47, 0.8)); z-index: 1;"></div>
    <div class="container position-relative text-center text-white" style="z-index: 2;">
        <h1 class="display-3 fw-bold mb-3 animate__animated animate__fadeInUp" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Measuring Our Change</h1>
        <p class="lead text-white-50 mx-auto animate__animated animate__fadeInUp animate__delay-1s" style="max-width: 600px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            Transparency is at the core of everything we do. See how your contributions are transforming lives.
        </p>
    </div>
</section>

<!-- Stats Counter -->
<section class="section-padding bg-white pb-0">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <h2 class="display-4 fw-bold text-orange counter">50k+</h2>
                <p class="text-muted text-uppercase fw-bold letter-spacing-1">Lives Impacted</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="display-4 fw-bold text-navy counter">120+</h2>
                <p class="text-muted text-uppercase fw-bold letter-spacing-1">Villages Served</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="display-4 fw-bold text-orange counter">15+</h2>
                <p class="text-muted text-uppercase fw-bold letter-spacing-1">Partner NGOs</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="display-4 fw-bold text-navy counter">₹1M+</h2>
                <p class="text-muted text-uppercase fw-bold letter-spacing-1">Funds Deployed</p>
            </div>
        </div>
        <hr class="mt-5 text-muted opacity-10">
    </div>
</section>

<!-- Financial Charts Placeholder -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h3 class="fw-bold text-navy mb-4">Where Your Money Goes</h3>
                <p class="text-muted mb-4">We maintain a strict 90:10 ratio. For every ₹100 donated, ₹90 goes directly to the program and only ₹10 is used for administrative costs to keep the foundation running efficiently.</p>
                <ul class="list-unstyled">
                    <li class="d-flex align-items-center mb-3">
                        <span class="d-inline-block rounded-circle me-3" style="width:20px; height:20px; background-color: var(--orange-vibrant);"></span>
                        <span class="fw-bold">60% Education Programs</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <span class="d-inline-block rounded-circle me-3" style="width:20px; height:20px; background-color: var(--navy-dark);"></span>
                        <span class="fw-bold">30% Medical Relief</span>
                    </li>
                     <li class="d-flex align-items-center mb-3">
                        <span class="d-inline-block rounded-circle me-3" style="width:20px; height:20px; bg-gray-300; background-color: #ccc;"></span>
                        <span class="fw-bold">10% Admin & Ops</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6 text-center">
                <!-- CSS Pie Chart Placeholder -->
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center position-relative shadow-lg" 
                     style="width: 300px; height: 300px; background: conic-gradient(var(--orange-vibrant) 0% 60%, var(--navy-dark) 60% 90%, #ccc 90% 100%);">
                     <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                         <div class="text-center">
                             <h2 class="fw-bold text-navy mb-0">100%</h2>
                             <small class="text-muted">Accountable</small>
                         </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Voices of Change (Slider) -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-orange text-uppercase letter-spacing-2 fw-bold">Testimonials</h6>
            <h2 class="fw-bold text-navy">Voices of Change</h2>
            <div class="d-flex justify-content-center gap-3">
                <a href="/public/testimonials.php" class="btn btn-outline-primary btn-sm rounded-pill px-4">View All Stories</a>
                <button class="btn btn-orange btn-sm rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#submitModal">
                    <i class="fas fa-pen-nib me-2"></i> Share Your Story
                </button>
            </div>
        </div>
        
        <?php
        $stmt = $conn->query("SELECT * FROM testimonials WHERE status = 'approved' ORDER BY created_at DESC LIMIT 5");
        $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($testimonials as $index => $t): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="text-center p-4">
                                <div class="mb-4">
                                    <?php if($t['image']): ?>
                                        <img src="/assets/uploads/testimonials/<?php echo htmlspecialchars($t['image']); ?>" class="rounded-circle shadow-sm object-fit-cover" width="80" height="80">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                                            <span class="fw-bold text-navy fs-4"><?php echo substr($t['name'], 0, 1); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <h4 class="fw-light fst-italic text-muted mb-4 lh-base">"<?php echo htmlspecialchars($t['message']); ?>"</h4>
                                <h5 class="fw-bold text-navy mb-0"><?php echo htmlspecialchars($t['name']); ?></h5>
                                <small class="text-orange fw-bold text-uppercase"><?php echo htmlspecialchars($t['role']); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-navy rounded-circle p-3" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-navy rounded-circle p-3" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<!-- Vertical Timeline -->
<section class="section-padding bg-gray-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-navy">Our Journey</h2>
            <p class="text-muted">A timeline of our milestones and growth.</p>
        </div>
        
        <div class="timeline position-relative">
            <!-- Vertical Line -->
            <div class="position-absolute top-0 bottom-0 start-50 translate-middle-x bg-orange" style="width: 2px;"></div>

            <!-- Item 1 (Left) -->
            <div class="row g-0 justify-content-between align-items-center mb-5 position-relative">
                <div class="col-md-5 text-md-end text-center mb-3 mb-md-0">
                    <div class="p-4 bg-white rounded-4 shadow-sm">
                        <h4 class="fw-bold text-navy">2023</h4>
                        <h5 class="fw-bold text-orange">1 Million Mark</h5>
                        <p class="text-muted mb-0 small">Crossed ₹1 Million in donations and impacted 50,000 lives across 3 states.</p>
                    </div>
                </div>
                <div class="col-md-2 text-center position-absolute top-50 start-50 translate-middle z-2">
                    <div class="bg-navy text-white rounded-circle d-flex align-items-center justify-content-center border border-4 border-white shadow" style="width: 50px; height: 50px;">
                        <i class="fas fa-flag"></i>
                    </div>
                </div>
                <div class="col-md-5"></div>
            </div>

            <!-- Item 2 (Right) -->
            <div class="row g-0 justify-content-between align-items-center mb-5 position-relative">
                <div class="col-md-5"></div>
                <div class="col-md-2 text-center position-absolute top-50 start-50 translate-middle z-2">
                     <div class="bg-white text-navy rounded-circle d-flex align-items-center justify-content-center border border-4 border-orange shadow" style="width: 50px; height: 50px;">
                        <i class="fas fa-medkit"></i>
                    </div>
                </div>
                <div class="col-md-5 text-md-start text-center">
                    <div class="p-4 bg-navy text-white rounded-4 shadow-sm">
                        <h4 class="fw-bold">2020</h4>
                        <h5 class="fw-bold text-orange">COVID Relief</h5>
                        <p class="text-white-50 mb-0 small">Distributed 10,000+ ration kits and essential gear during the pandemic peak.</p>
                    </div>
                </div>
            </div>

            <!-- Item 3 (Left) -->
            <div class="row g-0 justify-content-between align-items-center mb-5 position-relative">
                <div class="col-md-5 text-md-end text-center mb-3 mb-md-0">
                    <div class="p-4 bg-white rounded-4 shadow-sm">
                        <h4 class="fw-bold text-navy">2018</h4>
                        <h5 class="fw-bold text-orange">First School Built</h5>
                        <p class="text-muted mb-0 small">Completed 'Vidya Mandir' in rural Bihar, serving 500 students.</p>
                    </div>
                </div>
                <div class="col-md-2 text-center position-absolute top-50 start-50 translate-middle z-2">
                    <div class="bg-navy text-white rounded-circle d-flex align-items-center justify-content-center border border-4 border-white shadow" style="width: 50px; height: 50px;">
                        <i class="fas fa-school"></i>
                    </div>
                </div>
                <div class="col-md-5"></div>
            </div>

             <!-- Item 4 (Right) -->
             <div class="row g-0 justify-content-between align-items-center position-relative">
                <div class="col-md-5"></div>
                <div class="col-md-2 text-center position-absolute top-50 start-50 translate-middle z-2">
                     <div class="bg-white text-navy rounded-circle d-flex align-items-center justify-content-center border border-4 border-orange shadow" style="width: 50px; height: 50px;">
                        <i class="fas fa-rocket"></i>
                    </div>
                </div>
                <div class="col-md-5 text-md-start text-center">
                    <div class="p-4 bg-white rounded-4 shadow-sm">
                        <h4 class="fw-bold text-navy">2014</h4>
                        <h5 class="fw-bold text-orange">Foundation</h5>
                        <p class="text-muted mb-0 small">TrustFlow was established by Mr. Rajesh Kumar with a personal seed fund.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

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
