<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

session_unset();
session_destroy();

// Start fresh session just for message
session_start();
$_SESSION['logout_message'] = '✅ Successfully logged out.';
header('Location: ' . BASE_URL . '/user/login.php');
exit;
