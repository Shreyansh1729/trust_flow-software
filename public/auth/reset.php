<?php
require_once __DIR__ . '/../../includes/header.php';

$reset = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Placeholder logic
    $reset = true;
}
?>

<section class="py-5 bg-gray-light" style="min-height: 80vh;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-navy">Reset Password</h3>
                            <p class="text-muted small">Create a strong password for your account.</p>
                        </div>

                        <?php if ($reset): ?>
                            <div class="text-center">
                                <div class="mb-4 text-success">
                                    <i class="fas fa-check-circle fa-4x animate__animated animate__bounceIn"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Password Changed!</h4>
                                <p class="text-muted mb-4">Your password has been updated successfully.</p>
                                <a href="login.php" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold">
                                    Login Now
                                </a>
                            </div>
                        <?php else: ?>
                            <form method="POST">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="pass" name="password" placeholder="Password" required>
                                    <label for="pass">New Password</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="conf_pass" name="confirm_password" placeholder="Confirm Password" required>
                                    <label for="conf_pass">Confirm Password</label>
                                </div>
                                <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold">
                                    Update Password
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
