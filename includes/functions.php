<?php
// includes/functions.php

if (session_status() === PHP_SESSION_NONE) {
    // Security Hardening: Session Cookie Params
    session_set_cookie_params([
        'lifetime' => 86400, // 1 day
        'path' => '/',
        'domain' => '', // Default to current domain
        'secure' => isset($_SERVER['HTTPS']), // True if HTTPS
        'httponly' => true, // JavaScript cannot access
        'samesite' => 'Strict'
    ]);
    session_start();
}

/**
 * Generate CSRF Token
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verifyCsrfToken($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Regenerate Session (Security)
 */
function regenerateSession() {
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
}

/**
 * Sanitize user input
 */
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Redirect helper with Flash Message
 * @param string $url The URL to redirect to
 * @param string $message The message to display
 * @param string $type The type of alert (success, danger, warning, info)
 */
function redirect($url, $message = null, $type = 'success') {
    if ($message) {
        $_SESSION['flash_message'] = [
            'text' => $message,
            'type' => $type
        ];
    }
    header("Location: $url");
    exit();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Middleware to ensure only Admin can access
 */
function checkAdmin() {
    if (!isLoggedIn()) {
        redirect('/public/auth/login.php', 'Please login to access the admin panel.', 'warning');
    }
    
    if ($_SESSION['role'] !== 'admin') {
        redirect('/public/index.php', 'You do not have permission to access that page.', 'danger');
    }
}

/**
 * Middleware to ensure user is logged in
 */
function checkAuth() {
    if (!isLoggedIn()) {
        redirect('/public/auth/login.php', 'Please login to continue.', 'warning');
    }
}
?>
