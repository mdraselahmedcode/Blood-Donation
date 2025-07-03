<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php';

// Utility function for error responses
function error($msg) {
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// === Required fields ===
$fields = ['name', 'gender', 'email', 'phone', 'address', 'pin_code', 'city_id', 'country', 'password'];
foreach ($fields as $field) {
    $$field = trim($_POST[$field] ?? '');
}

// Optional donor flag
$is_donor = isset($_POST['is_donor']);
$blood_group_id = isset($_POST['blood_group_id']) ? intval($_POST['blood_group_id']) : null;

// === Validation ===
if ($name === '') error("Name is required.");
if (!in_array($gender, ['Male', 'Female', 'Other'])) error("Invalid gender selected.");
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) error("Invalid email address.");
if (!preg_match('/^01[3-9][0-9]{8}$/', $phone)) error("Invalid Bangladeshi phone number.");
if ($address === '') error("Address is required.");
if (!preg_match('/^\d{4}$/', $pin_code)) error("PIN Code must be 4 digits.");
if (!is_numeric($city_id)) error("Invalid city selected.");
if ($country === '') error("Country is required.");
if (strlen($password) < 6) error("Password must be at least 6 characters long.");

$city_id = intval($city_id);

// === Check if city exists ===
$cityCheck = $conn->prepare("SELECT id FROM cities WHERE id = ?");
$cityCheck->bind_param("i", $city_id);
$cityCheck->execute();
$cityCheck->store_result();
if ($cityCheck->num_rows === 0) error("Selected city does not exist.");
$cityCheck->close();

// === Donor-specific validation ===
if ($is_donor) {
    if (!$blood_group_id) error("Please select a valid blood group.");

    // Validate blood group exists
    $bgCheck = $conn->prepare("SELECT id FROM blood_groups WHERE id = ?");
    $bgCheck->bind_param("i", $blood_group_id);
    $bgCheck->execute();
    $bgCheck->store_result();
    if ($bgCheck->num_rows === 0) error("Selected blood group does not exist.");
    $bgCheck->close();

    // Check for duplicate email/phone in donors
    $dupCheck = $conn->prepare("SELECT id FROM donors WHERE email = ? OR phone = ?");
    $dupCheck->bind_param("ss", $email, $phone);
    $dupCheck->execute();
    $dupCheck->store_result();
    if ($dupCheck->num_rows > 0) error("Email or phone already exists for a donor.");
    $dupCheck->close();
} else {
    // Check for duplicate email/phone in users
    $dupCheck = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $dupCheck->bind_param("ss", $email, $phone);
    $dupCheck->execute();
    $dupCheck->store_result();
    if ($dupCheck->num_rows > 0) error("Email or phone already exists for a user.");
    $dupCheck->close();
}

// === Hash password securely ===
$hashedPassword = hash('sha256', $password);

// === Insert into appropriate table ===
if ($is_donor) {
    $stmt = $conn->prepare("INSERT INTO donors (name, gender, email, phone, address, pin_code, city_id, country, blood_group_id, password)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssisis", $name, $gender, $email, $phone, $address, $pin_code, $city_id, $country, $blood_group_id, $hashedPassword);
} else {
    $stmt = $conn->prepare("INSERT INTO users (name, gender, email, phone, address, pin_code, city_id, country, password)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiss", $name, $gender, $email, $phone, $address, $pin_code, $city_id, $country, $hashedPassword);
}

// === Execute insertion ===
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Statement preparation failed.']);
    exit;
}

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => $is_donor ? '✅ Donor registered successfully!' : '✅ User registered successfully!'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
