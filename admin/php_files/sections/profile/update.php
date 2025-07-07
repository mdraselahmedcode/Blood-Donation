<?php
session_start();
require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

header('Content-Type: application/json');

// Authorization check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$adminId = $_SESSION['admin_id'] ?? null;

// Helper function
function respondError($msg) {
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// Get and trim inputs
$firstName = trim($_POST['first_name'] ?? '');
$lastName  = trim($_POST['last_name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$gender    = trim($_POST['gender'] ?? '');
$country   = trim($_POST['country'] ?? '');

// Basic validation
if ($firstName === '') respondError('First name is required.');
if (strlen($firstName) < 2) respondError('First name must be at least 2 characters.');
if ($lastName === '') respondError('Last name is required.');
if (strlen($lastName) < 2) respondError('Last name must be at least 2 characters.');

if ($email === '') respondError('Email is required.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) respondError('Invalid email format.');

if ($phone === '') respondError('Phone number is required.');
if (!preg_match('/^01[3-9][0-9]{8}$/', $phone)) {
    respondError('Invalid Bangladeshi phone number. Format must be 01XXXXXXXXX.');
}

$allowedGenders = ['Male', 'Female', 'Other'];
if ($gender === '') respondError('Gender is required.');
if (!in_array($gender, $allowedGenders)) respondError('Invalid gender selection.');

if ($country === '') respondError('Country is required.');

// Optional: check if email or phone already exists for a different admin
$stmtCheck = $conn->prepare("SELECT id FROM admins WHERE (email = ? OR phone = ?) AND id != ?");
$stmtCheck->bind_param("ssi", $email, $phone, $adminId);
$stmtCheck->execute();
$stmtCheck->store_result();
if ($stmtCheck->num_rows > 0) {
    $stmtCheck->close();
    respondError('Email or phone already in use.');
}
$stmtCheck->close();

// Update profile
$stmt = $conn->prepare("UPDATE admins SET first_name = ?, last_name = ?, email = ?, phone = ?, gender = ?, country = ? WHERE id = ?");
$stmt->bind_param("ssssssi", $firstName, $lastName, $email, $phone, $gender, $country, $adminId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '✅ Profile updated successfully.']);
} else {
    respondError('❌ Failed to update profile. Please try again.');
}
$stmt->close();
$conn->close();
