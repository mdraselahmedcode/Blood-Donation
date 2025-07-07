<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php';

// Inputs
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$asDonor = isset($_POST['as_donor']); // checkbox

if ($email === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

$table = $asDonor ? 'donors' : 'users'; // determine table
$query = "SELECT id, name, email, password FROM $table WHERE email = ? LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();

// Check password
if ($user['password'] !== hash('sha256', $password)) {
    echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
    exit;
}

// Set session
$_SESSION['user_logged_in'] = true;
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_type'] = $asDonor ? 'donor' : 'user';
$_SESSION['is_donor'] = $asDonor ? 1 : 0;


echo json_encode([
    'success' => true,
    'message' => '✅ Login successful.',
    'redirect' => BASE_URL . '/user/dashboard.php'
]);
