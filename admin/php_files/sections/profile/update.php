<?php
session_start();
require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$adminId = $_SESSION['admin_id'] ?? null;
$firstName = trim($_POST['first_name']);
$lastName = trim($_POST['last_name']);
$email = trim($_POST['email']);

if (!$firstName || !$lastName || !$email) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$stmt = $conn->prepare("UPDATE admins SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
$stmt->bind_param("sssi", $firstName, $lastName, $email, $adminId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
}
$stmt->close();
