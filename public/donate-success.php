<?php
require_once __DIR__ . '/../includes/header.php';
$id = $_GET['id'] ?? 0;
?>

<div class="d-flex align-items-center justify-content-center bg-gray-light" style="min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-0 shadow-lg rounded-5 p-5 animate__animated animate__fadeInUp">
                    <div class="mb-4">
                         <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 3rem;">
                            <i class="fas fa-check"></i>
                         </div>
                    </div>
                    <h2 class="fw-bold text-navy mb-3">Thank You!</h2>
                    <p class="lead text-muted mb-4">Your donation has been received successfully. Your generosity helps us create lasting change.</p>
                    
                    <div class="card bg-light border-0 rounded-4 p-4 mb-4">
                        <p class="mb-0 text-muted small">A receipt has been sent to your email address.</p>
                        <?php if($id): ?>
                            <div class="mt-3">
                                <a href="/public/receipt-pdf.php?id=<?php echo $id; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                    <i class="fas fa-file-pdf me-2"></i>Download PDF Receipt
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <a href="/" class="btn btn-primary-custom px-5 py-3 rounded-pill fw-bold shadow-sm">Return Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
