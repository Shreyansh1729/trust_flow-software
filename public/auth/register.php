<?php
require_once '../../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Us - TrustFlow</title>
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
            <div class="auth-image" style="background-image: url('../../assets/img/cause-education.png');">
                <div class="auth-overlay">
                    <div class="text-white p-5">
                        <h1 class="display-4 fw-bold mb-3 text-white">Be the Change</h1>
                        <p class="lead">Join a community of changemakers. Your journey to making a difference starts here.</p>
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
                    <p class="text-muted mt-2">Create your donor account</p>
                </div>

                <!-- Alerts -->
                <?php include '../../includes/alerts.php'; ?>

                <form action="../../api/auth/register_process.php" method="POST" class="needs-validation" novalidate>
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                        <label for="name">Full Name</label>
                        <div class="invalid-feedback">Please enter your name.</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                        <label for="email">Email address</label>
                        <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>
                     
                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="1234567890">
                        <label for="phone">Phone Number (Optional)</label>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="6">
                                <label for="password">Password</label>
                                <div class="invalid-feedback">Min 6 chars required.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                <label for="confirm_password">Confirm</label>
                                <div class="invalid-feedback">Passwords must match.</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label text-muted small" for="terms">
                            I agree to the <a href="#" class="text-orange">Terms of Service</a> and <a href="#" class="text-orange">Privacy Policy</a>
                        </label>
                        <div class="invalid-feedback">You must agree to the terms.</div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-3 mb-4">
                        Create Account <i class="fas fa-user-plus ms-2"></i>
                    </button>

                    <div class="text-center">
                        <p class="text-muted">Already have an account? <a href="login.php" class="text-orange fw-bold">Sign in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scripts for validation and password matching
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        var pass = form.querySelector('#password');
                        var confirm = form.querySelector('#confirm_password');
                        
                        if(pass && confirm && pass.value !== confirm.value) {
                            confirm.setCustomValidity('Passwords do not match');
                        } else if(confirm) {
                            confirm.setCustomValidity('');
                        }

                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                    
                    // Real-time password check
                    var passInput = form.querySelector('#password');
                    var confirmInput = form.querySelector('#confirm_password');
                    if(passInput && confirmInput){
                        confirmInput.addEventListener('input', function(){
                             if(passInput.value !== confirmInput.value) {
                                confirmInput.setCustomValidity('Passwords do not match');
                            } else {
                                confirmInput.setCustomValidity('');
                            }
                        });
                    }
                })
        })()
    </script>
</body>
</html>
