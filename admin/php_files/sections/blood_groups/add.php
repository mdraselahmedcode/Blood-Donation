<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$name = strtoupper(trim($_POST['name'] ?? ''));

if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Blood group name is required.']);
    exit;
}

if (!preg_match('/^(A|B|AB|O)[+-]$/', $name)) {
    echo json_encode(['success' => false, 'message' => 'Invalid blood group format.']);
    exit;
}

// 🔍 Check for duplication manually before inserting
$checkStmt = $conn->prepare("SELECT id FROM blood_groups WHERE name = ?");
$checkStmt->bind_param("s", $name);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => '⚠️ This blood group already exists.']);
    $checkStmt->close();
    exit;
}
$checkStmt->close();

// ✅ Insert if not duplicate
$insertStmt = $conn->prepare("INSERT INTO blood_groups (name) VALUES (?)");
if (!$insertStmt) {
    echo json_encode(['success' => false, 'message' => 'Statement preparation failed.']);
    exit;
}

$insertStmt->bind_param("s", $name);
$success = $insertStmt->execute();

if ($success) {
    echo json_encode(['success' => true, 'message' => '✅ Blood group added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Error adding blood group: ' . $insertStmt->error]);
}

$insertStmt->close();
