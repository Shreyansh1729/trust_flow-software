<?php
// donor-panel/includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../includes/functions.php';

// Check Auth - ensure only logged in users (donors)
checkAuth(); 

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - TrustFlow</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <style>
        /* Specific overrides for donor panel horizontal layout */
        body {
            background-color: var(--gray-50);
        }
        .donor-nav {
            background-color: var(--white-pure);
            box-shadow: var(--shadow-sm);
        }
        .nav-link.active {
            color: var(--orange-vibrant) !important;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <!-- Horizontal Navbar -->
    <nav class="navbar navbar-expand-lg donor-nav sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-navy" href="/donor-panel/index.php">
                <i class="fas fa-hands-holding-heart text-orange me-2"></i> TrustFlow
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#donorNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="donorNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="/donor-panel/index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'history.php') ? 'active' : ''; ?>" href="/donor-panel/history.php">My Donation History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>" href="/donor-panel/profile.php">Profile</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <span class="me-3 text-muted small d-none d-md-block">Hello, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Donor'); ?></span>
                    <a href="/public/auth/logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container py-5">
        <?php include __DIR__ . '/../../includes/alerts.php'; ?>
