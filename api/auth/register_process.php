<?php
// api/auth/register_process.php

require_once '../../config/db.php';
require_once '../../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verify CSRF
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        redirect('../../public/auth/register.php', 'Invalid security token. Please try again.', 'danger');
    }
    
    // 1. Get and Clean Input
    $name = cleanInput($_POST['name'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $phone = cleanInput($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = 'donor'; // Default role

    // 2. basic Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        redirect('../../public/auth/register.php', 'All fields are required.', 'danger');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect('../../public/auth/register.php', 'Invalid email format.', 'danger');
    }

    if ($password !== $confirm_password) {
        redirect('../../public/auth/register.php', 'Passwords do not match.', 'danger');
    }
    
    if (strlen($password) < 6) {
        redirect('../../public/auth/register.php', 'Password must be at least 6 characters.', 'danger');
    }

    // 3. Database Interaction
    $db = new Database();
    $conn = $db->getConnection();

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    
    if ($stmt->rowCount() > 0) {
        redirect('../../public/auth/register.php', 'Email already registered. Please login.', 'warning');
    }

    // Hash Password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert User
    $sql = "INSERT INTO users (name, email, phone, password, role, created_at) VALUES (:name, :email, :phone, :password, :role, NOW())";
    $stmt = $conn->prepare($sql);
    
    try {
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $hashed_password,
            'role' => $role
        ]);
        
        redirect('../../public/auth/login.php', 'Registration successful! Please login to continue.', 'success');
        
    } catch (PDOException $e) {
        // Log error in a real app
        error_log("Registration Error: " . $e->getMessage());
        redirect('../../public/auth/register.php', 'System error encountered. Please try again later.', 'danger');
    }

} else {
    // If accessed directly without POST
    redirect('../../public/auth/register.php', 'Invalid request method.', 'danger');
}
