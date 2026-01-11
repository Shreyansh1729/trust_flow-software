<?php
// admin/includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../includes/functions.php';

// Force Admin Check
checkAdmin();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TrustFlow</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/admin/index.php" class="sidebar-brand">
                <i class="fas fa-shield-alt text-orange me-2"></i> TrustFlow
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="/admin/index.php" class="sidebar-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="/admin/donations.php" class="sidebar-link <?php echo ($current_page == 'donations.php') ? 'active' : ''; ?>">
                    <i class="fas fa-hand-holding-usd"></i> Donations
                </a>
            </li>
            <li>
                <a href="/admin/projects.php" class="sidebar-link <?php echo ($current_page == 'projects.php') ? 'active' : ''; ?>">
                    <i class="fas fa-project-diagram"></i> Projects
                </a>
            </li>
            <li>
                <a href="/admin/users.php" class="sidebar-link <?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li>
                <a href="/admin/inquiries.php" class="sidebar-link <?php echo ($current_page == 'inquiries.php') ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i> Inquiries
                </a>
            </li>
            <li>
                <a href="/admin/subscribers.php" class="sidebar-link <?php echo ($current_page == 'subscribers.php') ? 'active' : ''; ?>">
                    <i class="fas fa-rss"></i> Subscribers
                </a>
            </li>
            <li>
                <a href="/admin/volunteers.php" class="sidebar-link <?php echo ($current_page == 'volunteers.php') ? 'active' : ''; ?>">
                    <i class="fas fa-hands-helping"></i> Volunteers
                </a>
            </li>
            <li>
                <a href="/admin/media.php" class="sidebar-link <?php echo ($current_page == 'media.php') ? 'active' : ''; ?>">
                    <i class="fas fa-images"></i> Media
                </a>
            </li>
            <li>
                <a href="/admin/testimonials.php" class="sidebar-link <?php echo ($current_page == 'testimonials.php') ? 'active' : ''; ?>">
                    <i class="fas fa-quote-left"></i> Testimonials
                </a>
            </li>
            <li>
                <a href="/admin/team.php" class="sidebar-link <?php echo ($current_page == 'team.php') ? 'active' : ''; ?>">
                    <i class="fas fa-users-cog"></i> Team
                </a>
            </li>
            <li>
                <a href="/admin/blogs.php" class="sidebar-link <?php echo ($current_page == 'blogs.php') ? 'active' : ''; ?>">
                    <i class="fas fa-newspaper"></i> News & Blogs
                </a>
            </li>
            <li class="mt-4">
                <span class="px-4 text-muted small text-uppercase fw-bold">System</span>
            </li>
            <li>
                <a href="/admin/settings.php" class="sidebar-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
            <li>
                <a href="/public/auth/logout.php" class="sidebar-link text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <button class="menu-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <h2 class="topbar-title">Dashboard</h2>
            
            <div class="user-profile dropdown">
                <div class="d-flex align-items-center cursor-pointer" role="button" data-bs-toggle="dropdown">
                    <span class="me-2 fw-semibold d-none d-md-block">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?>
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-premium border-0">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/public/auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
            </div>
        </header>

        <!-- Page Content Container -->
        <div class="container-fluid p-4">
            <!-- Alert Placeholder -->
            <?php include __DIR__ . '/../../includes/alerts.php'; ?>
