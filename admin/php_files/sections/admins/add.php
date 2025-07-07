<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] !== true ||
    empty($_SESSION['super_admin']) ||
    $_SESSION['super_admin'] != 1
) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Helper function to send error response and exit
function error($msg) {
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// Trim all inputs
$fields = ['first_name', 'last_name', 'username', 'email', 'phone', 'gender', 'country', 'password', 'confirm_password'];
foreach ($fields as $field) {
    $$field = trim($_POST[$field] ?? '');
}

// Validation
if ($first_name === '') error("First name is required.");
if ($last_name === '') error("Last name is required.");
if ($username === '') error("Username is required.");
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) error("Invalid email address.");

// Bangladeshi phone number validation pattern: starts with 01, second digit 3-9, then 8 digits
if (!preg_match('/^01[3-9][0-9]{8}$/', $phone)) error("Invalid Bangladeshi phone number format.");

if (!in_array($gender, ['Male', 'Female', 'Other'])) error("Invalid gender selected.");
if ($country === '') error("Country is required.");

if (strlen($password) < 6) error("Password must be at least 6 characters long.");
if ($password !== $confirm_password) error("Passwords do not match.");

// Check duplicates for username, email, or phone
$stmtCheck = $conn->prepare("SELECT id FROM admins WHERE username = ? OR email = ? OR phone = ?");
$stmtCheck->bind_param("sss", $username, $email, $phone);
$stmtCheck->execute();
$stmtCheck->store_result();
if ($stmtCheck->num_rows > 0) {
    $stmtCheck->close();
    error("Username, email, or phone already exists.");
}
$stmtCheck->close();

// Hash password securely
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Super admin checkbox
$super_admin = isset($_POST['super_admin']) && $_POST['super_admin'] == '1' ? 1 : 0;

// Insert admin
$stmt = $conn->prepare("INSERT INTO admins (first_name, last_name, username, email, phone, gender, country, password, super_admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    error('Database error: ' . $conn->error);
}

$stmt->bind_param("ssssssssi", $first_name, $last_name, $username, $email, $phone, $gender, $country, $hashedPassword, $super_admin);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '✅ Admin added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
