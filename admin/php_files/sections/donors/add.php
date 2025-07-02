<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Utility to respond with error
function error($msg) {
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// Trim all inputs
$fields = ['name', 'gender', 'email', 'phone', 'address', 'pin_code', 'country', 'password'];
foreach ($fields as $field) {
    $$field = trim($_POST[$field] ?? '');
}

// Get city and blood group ids
$city_id = isset($_POST['city_id']) ? intval($_POST['city_id']) : null;
$blood_group_id = isset($_POST['blood_group_id']) ? intval($_POST['blood_group_id']) : null;

// ======== VALIDATION ========

if ($name === '') error("Name is required.");
if (!in_array($gender, ['Male', 'Female', 'Other'])) error("Invalid gender selected.");
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) error("Invalid email address.");
if (!preg_match('/^01[3-9][0-9]{8}$/', $phone)) error("Invalid Bangladeshi phone number format.");
if ($address === '') error("Address is required.");
if (!preg_match('/^\d{4}$/', $pin_code)) error("PIN Code must be 4 digits.");
if ($country === '') error("Country is required.");
if (strlen($password) < 6) error("Password must be at least 6 characters long.");

if (!$city_id || !$blood_group_id) error("City and Blood Group must be selected.");

// ==== Check if city exists ====
$cityCheck = $conn->prepare("SELECT id FROM cities WHERE id = ?");
$cityCheck->bind_param("i", $city_id);
$cityCheck->execute();
$cityCheck->store_result();
if ($cityCheck->num_rows === 0) error("Selected city does not exist.");
$cityCheck->close();

// ==== Check if blood group exists ====
$bgCheck = $conn->prepare("SELECT id FROM blood_groups WHERE id = ?");
$bgCheck->bind_param("i", $blood_group_id);
$bgCheck->execute();
$bgCheck->store_result();
if ($bgCheck->num_rows === 0) error("Selected blood group does not exist.");
$bgCheck->close();

// ==== Check for duplicate email or phone ====
$dupCheck = $conn->prepare("SELECT id FROM donors WHERE email = ? OR phone = ?");
$dupCheck->bind_param("ss", $email, $phone);
$dupCheck->execute();
$dupCheck->store_result();
if ($dupCheck->num_rows > 0) error("Email or phone number already exists.");
$dupCheck->close();

// Hash the password
$hashedPassword = hash('sha256', $password);

// Insert donor
$stmt = $conn->prepare("INSERT INTO donors (
    name, gender, email, phone, address, pin_code, city_id, country, blood_group_id, password
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Statement preparation failed.']);
    exit;
}

$stmt->bind_param(
    "ssssssisis",
    $name, $gender, $email, $phone, $address, $pin_code,
    $city_id, $country, $blood_group_id, $hashedPassword
);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '✅ Donor added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
