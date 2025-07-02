<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

// Check login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Sanitize and fetch POST values
$id         = intval($_POST['id'] ?? 0);
$name       = trim($_POST['name'] ?? '');
$gender     = $_POST['gender'] ?? '';
$email      = trim($_POST['email'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$password   = trim($_POST['password'] ?? '');
$address    = trim($_POST['address'] ?? '');
$pin_code   = trim($_POST['pin_code'] ?? '');
$city_id    = intval($_POST['city_id'] ?? 0);
$country    = trim($_POST['country'] ?? '');
$blood_id   = intval($_POST['blood_group_id'] ?? 0);

// === Validation ===

// Check required fields
if (!$id || $name === '' || $gender === '' || $email === '' || $phone === '' || $address === '' ||
    $pin_code === '' || !$city_id || $country === '' || !$blood_id) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Validate name
if (!preg_match('/^[a-zA-Z\s\.\-]{2,100}$/', $name)) {
    echo json_encode(['success' => false, 'message' => 'Invalid name format.']);
    exit;
}

// Validate gender
$validGenders = ['Male', 'Female', 'Other'];
if (!in_array($gender, $validGenders)) {
    echo json_encode(['success' => false, 'message' => 'Invalid gender.']);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit;
}

// Validate phone
if (!preg_match('/^[0-9+\-\s]{8,20}$/', $phone)) {
    echo json_encode(['success' => false, 'message' => 'Invalid phone number.']);
    exit;
}

// Validate password if provided
if ($password !== '' && strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
    exit;
}

// Validate pin code
if (!preg_match('/^\d{4,10}$/', $pin_code)) {
    echo json_encode(['success' => false, 'message' => 'Invalid PIN code.']);
    exit;
}

// Check if city exists
$cityCheck = $conn->prepare("SELECT id FROM cities WHERE id = ?");
$cityCheck->bind_param("i", $city_id);
$cityCheck->execute();
$cityCheck->store_result();
if ($cityCheck->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Selected city does not exist.']);
    exit;
}
$cityCheck->close();

// Check if blood group exists
$bloodCheck = $conn->prepare("SELECT id FROM blood_groups WHERE id = ?");
$bloodCheck->bind_param("i", $blood_id);
$bloodCheck->execute();
$bloodCheck->store_result();
if ($bloodCheck->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Selected blood group does not exist.']);
    exit;
}
$bloodCheck->close();

// Check for duplicate email
$emailCheck = $conn->prepare("SELECT id FROM donors WHERE email = ? AND id != ?");
$emailCheck->bind_param("si", $email, $id);
$emailCheck->execute();
$emailCheck->store_result();
if ($emailCheck->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'This email is already in use by another donor.']);
    exit;
}
$emailCheck->close();

// === UPDATE ===
if ($password !== '') {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE donors SET name = ?, gender = ?, email = ?, phone = ?, address = ?, pin_code = ?, city_id = ?, country = ?, blood_group_id = ?, password = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare update statement: ' . $conn->error]);
        exit;
    }
    // 11 parameters: sssss-s-isssi
    $stmt->bind_param("ssssssissssi", $name, $gender, $email, $phone, $address, $pin_code, $city_id, $country, $blood_id, $hashedPassword, $id);
} else {
    $stmt = $conn->prepare("UPDATE donors SET name = ?, gender = ?, email = ?, phone = ?, address = ?, pin_code = ?, city_id = ?, country = ?, blood_group_id = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare update statement: ' . $conn->error]);
        exit;
    }
    // 10 parameters: sssss-s-issi
    $stmt->bind_param("ssssssissi", $name, $gender, $email, $phone, $address, $pin_code, $city_id, $country, $blood_id, $id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '✅ Donor updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Failed to update donor: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
