<?php
require_once '../../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TrustFlow</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/custom-auth.css">
</head>
<body>

    <div class="row g-0 vh-100">
        <!-- Left Side: Image -->
        <div class="col-lg-6 d-none d-lg-block">
            <div class="auth-image" style="background-image: url('../../assets/img/hero-home.png');">
                <div class="auth-overlay">
                    <div class="text-white p-5">
                        <h1 class="display-4 fw-bold mb-3 text-white">Welcome Back</h1>
                        <p class="lead text-white">Your generosity is transforming lives. Log in to track your impact and discover new ways to help.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="col-lg-6 d-flex align-items-center bg-white justify-content-center">
            <div class="auth-form-container p-5 w-100" style="max-width: 550px;">
                <div class="text-center mb-5">
                    <a href="../../public/index.php" class="text-decoration-none">
                        <h2 class="fw-bold text-navy"><i class="fas fa-hands-holding-heart text-orange me-2"></i>TrustFlow</h2>
                    </a>
                    <p class="text-muted mt-2">Sign in to your account</p>
                </div>

                <!-- Alerts -->
                <?php include '../../includes/alerts.php'; ?>

                <form action="../../api/auth/login_process.php" method="POST" class="needs-validation" novalidate>
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                        <label for="email">Email address</label>
                        <div class="invalid-feedback">Please enter your email.</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                        <div class="invalid-feedback">Please enter your password.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label text-muted" for="remember">Remember me</label>
                        </div>
                        <a href="forgot-password.php" class="text-orange small fw-bold">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-3 mb-4">
                        Sign In <i class="fas fa-sign-in-alt ms-2"></i>
                    </button>

                    <div class="text-center">
                        <p class="text-muted">Don't have an account? <a href="register.php" class="text-orange fw-bold">Sign up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple client-side validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>
