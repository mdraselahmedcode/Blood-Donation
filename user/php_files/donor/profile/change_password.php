<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

function error($msg) {
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// Only allow if donor
if (
    !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ||
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'donor'
) {
    error('Unauthorized or not a donor.');
}

$userId = intval($_SESSION['user_id']);

// Get and validate inputs
$current_password = trim($_POST['current_password'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

if ($current_password === '' || $new_password === '' || $confirm_password === '') {
    error('All password fields are required.');
}
if (strlen($new_password) < 6) {
    error('New password must be at least 6 characters.');
}
if ($new_password !== $confirm_password) {
    error('New password and confirm password do not match.');
}

// Fetch current password hash
$stmt = $conn->prepare("SELECT password FROM donors WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($db_password);
if (!$stmt->fetch()) {
    $stmt->close();
    error('Donor not found.');
}
$stmt->close();

// Check current password
if ($db_password !== hash('sha256', $current_password)) {
    error('Current password is incorrect.');
}

// Update password
$new_hash = hash('sha256', $new_password);
$update = $conn->prepare("UPDATE donors SET password = ? WHERE id = ?");
$update->bind_param("si", $new_hash, $userId);

if ($update->execute()) {
    echo json_encode(['success' => true, 'message' => 'Password changed successfully.']);
} else {
    error('Failed to update password.');
}
$update->close();
$conn->close();