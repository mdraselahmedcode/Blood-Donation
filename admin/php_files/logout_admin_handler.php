<?php
session_start(); 
require_once __DIR__ . '/../../config/config.php'; 

// Clear session data
$_SESSION = [];
session_destroy();

// Start a new session for flash message
session_start();
$_SESSION['logout_message'] = "Logout successful.";

// Redirect to login
header("Location:" . BASE_URL . "/admin/login.php");
exit(); 
?>
