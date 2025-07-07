<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

function error($msg) {
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    error('Unauthorized. Please log in as admin.');
}

$userId = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($userId <= 0) {
    error('Invalid user ID.');
}

$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $stmt->close();
    error('User not found.');
}
$stmt->close();

$del = $conn->prepare("DELETE FROM users WHERE id = ?");
$del->bind_param("i", $userId);
if ($del->execute()) {
    echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
} else {
    error('Failed to delete user.');
}
$del->close();
$conn->close();