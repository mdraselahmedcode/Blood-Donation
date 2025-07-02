<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$name = trim($_POST['name'] ?? '');
if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'City name is required.']);
    exit;
}

// Check for duplicate
$checkStmt = $conn->prepare("SELECT id FROM cities WHERE name = ?");
$checkStmt->bind_param("s", $name);
$checkStmt->execute();
$checkStmt->store_result();
if ($checkStmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'This city already exists.']);
    $checkStmt->close();
    exit;
}
$checkStmt->close();

// Insert
$insertStmt = $conn->prepare("INSERT INTO cities (name) VALUES (?)");
if (!$insertStmt) {
    echo json_encode(['success' => false, 'message' => 'Statement preparation failed.']);
    exit;
}
$insertStmt->bind_param("s", $name);
$success = $insertStmt->execute();

if ($success) {
    echo json_encode(['success' => true, 'message' => 'City added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding city: ' . $insertStmt->error]);
}
$insertStmt->close();
$conn->close();