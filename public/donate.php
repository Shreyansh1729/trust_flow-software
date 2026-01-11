<?php
// public/donate.php
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section" style="background-image: url('../assets/img/cause-education.png'); height: 50vh; min-height: 400px;">
    <div class="container hero-content text-center">
        <h1 class="animate__animated animate__fadeInUp">Make a Difference Today</h1>
        <p class="lead text-white animate__animated animate__fadeInUp animate__delay-1s">
            Your contribution changes lives immediately.
        </p>
    </div>
</section>

<!-- Donation Form Section -->
<section class="section-padding position-relative" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container position-relative z-2">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-5 overflow-hidden" style="margin-top: -100px;">
                    <div class="row g-0">
                        <!-- Left: Visual & Trust -->
                        <div class="col-lg-5 position-relative d-none d-lg-block">
                            <!-- Background Image with Overlay -->
                            <div class="position-absolute w-100 h-100" style="background-image: url('../assets/img/cause_education.png'); background-size: cover; background-position: center;"></div>
                            <div class="position-absolute w-100 h-100 bg-navy" style="opacity: 0.9;"></div>
                            
                            <!-- Content -->
                            <div class="position-relative h-100 p-5 d-flex flex-column justify-content-center text-white text-center">
                                <div class="mb-4">
                                    <div class="bg-white text-navy rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg" style="width: 80px; height: 80px; font-size: 2rem;">
                                        <i class="fas fa-hand-holding-heart"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-3">Your Giving Matters</h3>
                                <p class="text-white-50 mb-5">100% of your donation goes directly to the causes you care about.</p>
                                
                                <div class="d-flex flex-column gap-3 text-start mx-auto" style="max-width: 250px;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-invoice text-orange me-3 fa-lg"></i>
                                        <span class="fw-medium">Tax Deductible (80G)</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-shield-alt text-orange me-3 fa-lg"></i>
                                        <span class="fw-medium">Secure Payment</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-receipt text-orange me-3 fa-lg"></i>
                                        <span class="fw-medium">Instant Reciept</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right: Form -->
                        <div class="col-lg-7 bg-white p-5">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold text-navy">Make a Contribution</h3>
                                <p class="text-muted small">Select an amount or enter your own.</p>
                            </div>

                            <form id="donation-form">
                                
                                <!-- Amount Presets -->
                                <div class="row g-3 mb-4">
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="amount" id="amt1" value="500" autocomplete="off">
                                        <label class="btn btn-outline-light text-dark w-100 py-3 rounded-4 border-2 fw-bold d-flex flex-column align-items-center justify-content-center gap-1 shadow-sm h-100 card-hover-scale" for="amt1" style="border-color: #dee2e6;">
                                            <span class="small text-muted">SUPPORTER</span>
                                            <span class="fs-4">₹500</span>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="amount" id="amt2" value="1000" autocomplete="off" checked>
                                        <label class="btn btn-outline-light text-dark w-100 py-3 rounded-4 border-2 fw-bold d-flex flex-column align-items-center justify-content-center gap-1 shadow-sm h-100 card-hover-scale" for="amt2" style="border-color: #dee2e6;">
                                            <span class="small text-muted">HERO</span>
                                            <span class="fs-4">₹1,000</span>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="amount" id="amt3" value="5000" autocomplete="off">
                                        <label class="btn btn-outline-light text-dark w-100 py-3 rounded-4 border-2 fw-bold d-flex flex-column align-items-center justify-content-center gap-1 shadow-sm h-100 card-hover-scale" for="amt3" style="border-color: #dee2e6;">
                                            <span class="small text-muted">CHAMPION</span>
                                            <span class="fs-4">₹5,000</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Custom Amount -->
                                <div class="form-floating mb-4">
                                    <input type="number" class="form-control bg-light border-0 fw-bold fs-5" id="customAmount" name="custom_amount" placeholder="Other Amount">
                                    <label for="customAmount" class="text-muted">Or enter other amount (₹)</label>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-light border-0" id="name" placeholder="Full Name" required>
                                            <label for="name">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control bg-light border-0" id="email" placeholder="Email Address" required>
                                            <label for="email">Email Address</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control bg-light border-0" id="pan" placeholder="PAN Number" style="text-transform: uppercase;" maxlength="10">
                                            <label for="pan">PAN Number (Required for Tax Exemption)</label>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-orange w-100 py-3 rounded-pill fw-bold fs-5 shadow-lg scale-on-hover">
                                    Complete Secure Donation <i class="fas fa-lock ms-2"></i>
                                </button>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted"><i class="fas fa-shield-alt me-1"></i> Your transaction is SSL encrypted and secure.</small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Custom Selected State for Buttons */
.btn-check:checked + .btn-outline-light {
    background-color: #fff7ed !important; /* Orange Tint Light */
    border-color: var(--orange-vibrant) !important;
    color: var(--navy-dark) !important;
    box-shadow: 0 4px 12px rgba(249, 115, 22, 0.2) !important;
}
.card-hover-scale {
    transition: all 0.2s ease;
}
.card-hover-scale:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
}
.scale-on-hover {
    transition: transform 0.2s ease;
}
.scale-on-hover:hover {
    transform: scale(1.02);
}
</style>

<!-- Razorpay SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<!-- Custom Donation Logic -->
<script src="/assets/js/donation.js"></script>

<!-- Handle Radios/Custom Input Interaction -->
<script>
    document.getElementById('customAmount').addEventListener('focus', function() {
        document.querySelectorAll('input[name="amount"]').forEach(r => r.checked = false);
    });
    
    document.querySelectorAll('input[name="amount"]').forEach(r => {
        r.addEventListener('change', function() {
            document.getElementById('customAmount').value = '';
        });
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
