<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$id = intval($_POST['id'] ?? 0);
$name = strtoupper(trim($_POST['name'] ?? ''));

if ($id <= 0 || $name === '') {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

if (!preg_match('/^(A|B|AB|O)[+-]$/', $name)) {
    echo json_encode(['success' => false, 'message' => 'Invalid blood group format.']);
    exit;
}

// Check for duplicate (excluding current)
$check = $conn->prepare("SELECT id FROM blood_groups WHERE name = ? AND id != ?");
$check->bind_param("si", $name, $id);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'This blood group already exists.']);
    $check->close();
    exit;
}
$check->close();

// Update
$stmt = $conn->prepare("UPDATE blood_groups SET name = ? WHERE id = ?");
$stmt->bind_param("si", $name, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '✅ Blood group updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Failed to update blood group.']);
}

$stmt->close();
$conn->close();
