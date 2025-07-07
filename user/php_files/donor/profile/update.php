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

// Collect and sanitize inputs
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$gender   = trim($_POST['gender'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$address  = trim($_POST['address'] ?? '');
$pin_code = trim($_POST['pin_code'] ?? '');
$city_id  = isset($_POST['city_id']) ? intval($_POST['city_id']) : null;
$country  = trim($_POST['country'] ?? '');
$blood_group_id = isset($_POST['blood_group_id']) ? intval($_POST['blood_group_id']) : null;
$status   = trim($_POST['status'] ?? 'Active');

// ======== VALIDATION ========
if ($name === '') error("Name is required.");
if (!in_array($gender, ['Male', 'Female', 'Other'])) error("Invalid gender selected.");
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) error("Invalid email address.");
if (!preg_match('/^01[3-9][0-9]{8}$/', $phone)) error("Invalid Bangladeshi phone number format.");
if ($address === '') error("Address is required.");
if (!preg_match('/^\d{4}$/', $pin_code)) error("PIN Code must be 4 digits.");
if ($country === '') error("Country is required.");
if (!$city_id) error("City must be selected.");
if (!$blood_group_id) error("Blood group must be selected.");
if (!in_array($status, ['Active', 'Inactive'])) error("Invalid status.");

// ==== Check if city exists ====
$cityCheck = $conn->prepare("SELECT id FROM cities WHERE id = ?");
$cityCheck->bind_param("i", $city_id);
$cityCheck->execute();
$cityCheck->store_result();
if ($cityCheck->num_rows === 0) error("Selected city does not exist.");
$cityCheck->close();

// ==== Check if blood group exists ====
$groupCheck = $conn->prepare("SELECT id FROM blood_groups WHERE id = ?");
$groupCheck->bind_param("i", $blood_group_id);
$groupCheck->execute();
$groupCheck->store_result();
if ($groupCheck->num_rows === 0) error("Selected blood group does not exist.");
$groupCheck->close();

// ==== Check for duplicate email (excluding self) ====
$dupCheck = $conn->prepare("SELECT id FROM donors WHERE email = ? AND id != ?");
$dupCheck->bind_param("si", $email, $userId);
$dupCheck->execute();
$dupCheck->store_result();
if ($dupCheck->num_rows > 0) error("This email is already in use.");
$dupCheck->close();

// ==== Check for duplicate phone (excluding self) ====
$dupPhone = $conn->prepare("SELECT id FROM donors WHERE phone = ? AND id != ?");
$dupPhone->bind_param("si", $phone, $userId);
$dupPhone->execute();
$dupPhone->store_result();
if ($dupPhone->num_rows > 0) error("This phone number is already in use.");
$dupPhone->close();

// ==== Update donor profile ====
$stmt = $conn->prepare("UPDATE donors SET name=?, email=?, gender=?, phone=?, address=?, pin_code=?, city_id=?, country=?, blood_group_id=?, status=? WHERE id=?");
$stmt->bind_param("ssssssisisi", $name, $email, $gender, $phone, $address, $pin_code, $city_id, $country, $blood_group_id, $status, $userId);

if ($stmt->execute()) {
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    error('Failed to update profile.');
}
$stmt->close();
$conn->close();