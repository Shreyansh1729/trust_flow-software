<?php
// public/auth/logout.php
require_once '../../includes/functions.php';

// Destroy all session data
session_destroy();

// Start a new session for the redirect message
session_start();
redirect('/public/index.php', 'You have been logged out successfully.', 'info');
