<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

// --- 1. Access control ---
if (
    !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ||
    empty($_SESSION['super_admin']) || $_SESSION['super_admin'] != 1
) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// --- 2. Confirm current admin is protected ---
$currentAdminId = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT is_protected FROM admins WHERE id = ?");
$stmt->bind_param("i", $currentAdminId);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$current || $current['is_protected'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Only a protected super admin can update admin data.']);
    exit;
}

// --- 3. Helper for clean errors ---
function error($msg) {
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// --- 4. Extract and sanitize POST data ---
$fields = ['id', 'first_name', 'last_name', 'username', 'email', 'phone', 'gender', 'country', 'password', 'confirm_password', 'super_admin'];
foreach ($fields as $field) {
    $$field = trim($_POST[$field] ?? '');
}

$id = intval($id);
if ($id <= 0) error('Invalid admin ID.');

if ($first_name === '') error("First name is required.");
if ($last_name === '') error("Last name is required.");
if ($username === '') error("Username is required.");
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) error("Invalid email address.");

// ✅ Bangladeshi phone validation: starts with 013–019, total 11 digits
if (!preg_match('/^01[3-9][0-9]{8}$/', $phone)) {
    error("Invalid Bangladeshi phone number format.");
}

if (!in_array($gender, ['Male', 'Female', 'Other'])) error("Invalid gender selected.");
if ($country === '') error("Country is required.");

$requested_super_admin = ($super_admin === '1' || $super_admin === 1) ? 1 : 0;

// --- 5. Validate passwords if entered ---
if ($password !== '' || $confirm_password !== '') {
    if (strlen($password) < 6) error("Password must be at least 6 characters long.");
    if ($password !== $confirm_password) error("Passwords do not match.");
}

// --- 6. Check for duplicates ---
$stmt = $conn->prepare("SELECT id FROM admins WHERE (username = ? OR email = ? OR phone = ?) AND id != ?");
$stmt->bind_param("sssi", $username, $email, $phone, $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    error("Username, email, or phone already exists.");
}
$stmt->close();

// --- 7. Fetch target admin’s protection status ---
$stmt = $conn->prepare("SELECT is_protected FROM admins WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$adminData = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$adminData) error("Admin not found.");
$is_protected = (int)$adminData['is_protected'];

// --- 8. Super Admin flag enforcement ---
$final_super_admin = ($is_protected === 1) ? 1 : $requested_super_admin;

// --- 9. Build and execute update query ---
if ($password !== '') {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE admins SET first_name=?, last_name=?, username=?, email=?, phone=?, gender=?, country=?, password=?, super_admin=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "ssssssssii",
        $first_name,
        $last_name,
        $username,
        $email,
        $phone,
        $gender,
        $country,
        $hashedPassword,
        $final_super_admin,
        $id
    );
} else {
    $query = "UPDATE admins SET first_name=?, last_name=?, username=?, email=?, phone=?, gender=?, country=?, super_admin=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssssssii",
        $first_name,
        $last_name,
        $username,
        $email,
        $phone,
        $gender,
        $country,
        $final_super_admin,
        $id
    );
}

if (!$stmt) error("Database error: " . $conn->error);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '✅ Admin updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Update failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
