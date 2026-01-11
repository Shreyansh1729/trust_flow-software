<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex align-items-center justify-content-center" style="min-height: 70vh; background-color: var(--gray-light);">
    <div class="text-center p-4">
        <div class="mb-4 text-orange opacity-50">
            <i class="fas fa-search fa-8x"></i>
        </div>
        <h1 class="display-1 fw-bold text-navy">404</h1>
        <h2 class="h4 text-muted mb-4">Oops! The page you're looking for doesn't exist.</h2>
        
        <div class="d-flex gap-3 justify-content-center">
            <a href="/public/index.php" class="btn btn-primary-custom px-4 rounded-pill">
                <i class="fas fa-home me-2"></i> Go Home
            </a>
            <a href="/public/contact.php" class="btn btn-outline-custom px-4 rounded-pill">
                Contact Us
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
