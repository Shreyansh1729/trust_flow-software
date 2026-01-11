<?php
require_once __DIR__ . '/../../includes/header.php';

$sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Placeholder logic for simulation
    $sent = true;
}
?>

<section class="py-5 bg-gray-light" style="min-height: 80vh;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-navy">Forgot Password?</h3>
                            <p class="text-muted small">Enter your email and we'll send you instructions to reset your password.</p>
                        </div>

                        <?php if ($sent): ?>
                            <div class="alert alert-success text-center" role="alert">
                                <i class="fas fa-check-circle me-2"></i> Reset link sent! Check your inbox.
                                <br><br>
                                <a href="reset.php" class="btn btn-sm btn-outline-success">Simulate Link Click</a>
                            </div>
                        <?php else: ?>
                            <form method="POST">
                                <div class="form-floating mb-4">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                    <label for="email">Email Address</label>
                                </div>
                                <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold">
                                    Send Reset Link
                                </button>
                            </form>
                        <?php endif; ?>

                        <div class="text-center mt-4">
                            <a href="login.php" class="text-decoration-none text-muted small hover-underline">
                                <i class="fas fa-arrow-left me-1"></i> Back to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
