<?php
// api/auth/login_process.php

require_once '../../config/db.php';
require_once '../../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verify CSRF
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        redirect('../../public/auth/login.php', 'Session expired or invalid token. Please refresh and try again.', 'danger');
    }
    
    // 1. Get and Clean Input
    $email = cleanInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // 2. Validation
    if (empty($email) || empty($password)) {
        redirect('../../public/auth/login.php', 'Please enter both email and password.', 'danger');
    }

    // 3. Database Interaction
    $db = new Database();
    $conn = $db->getConnection();

    // Fetch user by email
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify Password
        if (password_verify($password, $user['password'])) {
            // Success: Start Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                redirect('../../admin/index.php', 'Welcome back, Admin!', 'success');
            } else {
                // Default to donor/volunteer panel or home
                redirect('../../donor-panel/index.php', 'Welcome back, ' . $user['name'] . '!', 'success');
            }
            
        } else {
            // Invalid Password
            redirect('../../public/auth/login.php', 'Invalid password. Please try again.', 'danger');
        }
    } else {
        // User not found
        redirect('../../public/auth/login.php', 'No account found with this email.', 'danger');
    }

} else {
    redirect('../../public/auth/login.php', 'Invalid request.', 'danger');
}
