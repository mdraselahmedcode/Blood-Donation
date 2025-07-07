<?php
session_start(); 
header('Content-Type: application/json'); 

require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php'; // Assumes this returns $conn = new mysqli(...)

$usernameOrEmail = trim($_POST['username'] ?? ''); // You can call it just username or email in frontend
$password = trim($_POST['password'] ?? '');

$errors = [];

if (empty($usernameOrEmail) || empty($password)) {
    $errors[] = 'Username or Email and Password are required.';
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Prepare the query to check for matching username OR email
$sql = "SELECT * FROM admins WHERE username = ? OR email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'errors' => ['Admin account not found.']]);
    exit;
}

$admin = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $admin['password'])) {
    echo json_encode(['success' => false, 'errors' => ['Incorrect password.']]);
    exit;
}

// Login successful
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_name'] = $admin['first_name'] . ' ' . $admin['last_name'];
$_SESSION['admin_username'] = $admin['username'];
$_SESSION['super_admin'] = isset($admin['super_admin']) && $admin['super_admin'] == 1 ? 1 : 0;
$_SESSION['is_protected'] = isset($admin['is_protected']) && $admin['is_protected'] == 1 ? 1 : 0;  // << Add this line


echo json_encode(['success' => true, 'message' => 'Login successful.']);
exit;
?>

