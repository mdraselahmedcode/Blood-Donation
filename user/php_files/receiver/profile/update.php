<?php
// filepath: e:\xamp_for_database_donwloading_folder\xampp_installing_folder\htdocs\blood-donate\user\php_files\profile\update.php

session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

// Utility to respond with error
function error($msg) {
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// Ensure user is logged in and is NOT a donor
if (
    !isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ||
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'user'
) {
    error('Unauthorized or not a normal user.');
}

$userId = intval($_SESSION['user_id']);

// Collect and sanitize inputs
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$gender   = trim($_POST['gender'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$address  = trim($_POST['address'] ?? '');
$pin_code = trim($_POST['pin_code'] ?? '');
$city_id  = isset($_POST['city_id']) ? intval($_POST['city_id']) : null;
$country  = trim($_POST['country'] ?? '');

// ======== VALIDATION ========

if ($name === '') error("Name is required.");
if (!in_array($gender, ['Male', 'Female', 'Other'])) error("Invalid gender selected.");
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) error("Invalid email address.");
if (!preg_match('/^01[3-9][0-9]{8}$/', $phone)) error("Invalid Bangladeshi phone number format.");
if ($address === '') error("Address is required.");
if (!preg_match('/^\d{4}$/', $pin_code)) error("PIN Code must be 4 digits.");
if ($country === '') error("Country is required.");
if (!$city_id) error("City must be selected.");

// ==== Check if city exists ====
$cityCheck = $conn->prepare("SELECT id FROM cities WHERE id = ?");
$cityCheck->bind_param("i", $city_id);
$cityCheck->execute();
$cityCheck->store_result();
if ($cityCheck->num_rows === 0) error("Selected city does not exist.");
$cityCheck->close();

// ==== Check for duplicate email (excluding self) ====
$dupCheck = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$dupCheck->bind_param("si", $email, $userId);
$dupCheck->execute();
$dupCheck->store_result();
if ($dupCheck->num_rows > 0) error("This email is already in use.");
$dupCheck->close();

// ==== Check for duplicate phone (excluding self) ====
$dupPhone = $conn->prepare("SELECT id FROM users WHERE phone = ? AND id != ?");
$dupPhone->bind_param("si", $phone, $userId);
$dupPhone->execute();
$dupPhone->store_result();
if ($dupPhone->num_rows > 0) error("This phone number is already in use.");
$dupPhone->close();

// ==== Update user profile ====
$stmt = $conn->prepare("UPDATE users SET name=?, email=?, gender=?, phone=?, address=?, pin_code=?, city_id=?, country=? WHERE id=?");
$stmt->bind_param("sssssssii", $name, $email, $gender, $phone, $address, $pin_code, $city_id, $country, $userId);

if ($stmt->execute()) {
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    error('Failed to update profile.');
}
$stmt->close();
$conn->close();