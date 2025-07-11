<?php
session_start();
require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$id = intval($_POST['id']);
if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid group ID.']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM blood_groups WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Blood group deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete blood group.']);
}
$stmt->close();
$conn->close();