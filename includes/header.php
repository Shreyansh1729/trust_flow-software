<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustFlow - Empowering Communities, Transforming Lives</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <!-- Premium Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom navbar-transparent fixed-top">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand" href="/public/index.php">
                <i class="fas fa-hands-holding-heart me-2"></i>TrustFlow
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Desktop Navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <!-- Home -->
                    <li class="nav-item">
                        <a class="nav-link" href="/public/index.php">Home</a>
                    </li>
                    
                    <!-- About Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            About
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/public/about.php"><i class="fas fa-book-open me-2"></i>Our Story</a></li>
                            <li><a class="dropdown-item" href="/public/team.php"><i class="fas fa-users me-2"></i>Team & Trustees</a></li>
                        </ul>
                    </li>
                    
                    <!-- Programs Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Programs
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/public/projects.php"><i class="fas fa-hand-holding-heart me-2"></i>Our Causes</a></li>
                            <li><a class="dropdown-item" href="/public/donate.php"><i class="fas fa-donate me-2"></i>Donate</a></li>
                        </ul>
                    </li>
                    
                    <!-- Impact -->
                    <li class="nav-item">
                        <a class="nav-link" href="/public/impact.php">Impact</a>
                    </li>
                    
                    <!-- Media Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Media
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/public/media.php"><i class="fas fa-images me-2"></i>Gallery</a></li>
                            <!-- Blog moved to top level -->
                        </ul>
                    </li>

                    <!-- Blog (Top Level) -->
                    <li class="nav-item">
                        <a class="nav-link" href="/public/blog.php">Blog</a>
                    </li>
                    
                    <!-- Volunteer (New) -->
                    <li class="nav-item">
                        <a class="nav-link" href="/public/volunteer.php">Volunteer</a>
                    </li>
                    
                    <!-- Contact -->
                    <li class="nav-item">
                        <a class="nav-link" href="/public/contact.php">Contact</a>
                    </li>
                </ul>
                
                <!-- Donate Button -->
                <div class="d-flex">
                    <!-- Added inline style to force visibility if CSS class has issues, but best to rely on class update below if possible. 
                         However, user reported colour visibility issues. Let's make it always gradient. -->
                    <a href="/public/donate.php" class="btn btn-donate shadow-glow text-white" style="background: linear-gradient(135deg, #F97316, #EA580C) !important; color: white !important;">
                        Donate Now <i class="fas fa-heart ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Off-Canvas Menu -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title text-white" id="mobileMenuLabel">
                <i class="fas fa-hands-holding-heart me-2"></i>TrustFlow
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/public/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/about.php">Our Story</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/team.php">Team & Trustees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/projects.php">Our Causes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/impact.php">Impact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/media.php">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/blog.php">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/volunteer.php">Volunteer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/public/contact.php">Contact</a>
                </li>
                <li class="nav-item mt-3">
                    <a href="/public/donate.php" class="btn btn-donate w-100 text-white" style="background: linear-gradient(135deg, #F97316, #EA580C) !important;">
                        Donate Now <i class="fas fa-heart ms-2"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
