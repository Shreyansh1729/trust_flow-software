<?php
require_once __DIR__ . '/../includes/header.php';
$reason = $_GET['reason'] ?? 'Transaction failed';
?>

<div class="d-flex align-items-center justify-content-center bg-gray-light" style="min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-0 shadow-lg rounded-5 p-5 animate__animated animate__fadeInUp">
                    <div class="mb-4">
                         <div class="bg-danger-subtle text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 3rem;">
                            <i class="fas fa-times"></i>
                         </div>
                    </div>
                    <h2 class="fw-bold text-navy mb-3">Donation Failed</h2>
                    <p class="lead text-muted mb-4">We couldn't process your donation. Please try again.</p>
                    
                    <div class="alert alert-warning border-0 rounded-4 mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i> <?php echo htmlspecialchars($reason); ?>
                    </div>

                    <div class="d-flex gap-3 justify-content-center">
                        <a href="/public/donate.php" class="btn btn-orange px-5 py-3 rounded-pill fw-bold shadow-sm">Try Again</a>
                        <a href="/" class="btn btn-outline-secondary px-5 py-3 rounded-pill fw-bold">Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
