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
$name = trim($_POST['name'] ?? '');

if ($id <= 0 || $name === '') {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

// Check for duplicate name (excluding current)
$check = $conn->prepare("SELECT id FROM cities WHERE name = ? AND id != ?");
$check->bind_param("si", $name, $id);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'This city name already exists.']);
    $check->close();
    exit;
}
$check->close();

// Update city
$update = $conn->prepare("UPDATE cities SET name = ? WHERE id = ?");
$update->bind_param("si", $name, $id);
if ($update->execute()) {
    echo json_encode(['success' => true, 'message' => 'City updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update city.']);
}
$update->close();
$conn->close();
