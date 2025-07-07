<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

// Utility function
function error($msg) {
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

// Check admin session
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    error('Unauthorized. Please log in as admin.');
}

// Validate donor ID
$donorId = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($donorId <= 0) {
    error('Invalid donor ID.');
}

// Check if donor exists
$stmt = $conn->prepare("SELECT id FROM donors WHERE id = ?");
$stmt->bind_param("i", $donorId);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $stmt->close();
    error('Donor not found.');
}
$stmt->close();

// Delete donor
$del = $conn->prepare("DELETE FROM donors WHERE id = ?");
$del->bind_param("i", $donorId);
if ($del->execute()) {
    echo json_encode(['success' => true, 'message' => 'Donor deleted successfully.']);
} else {
    error('Failed to delete donor.');
}
$del->close();
$conn->close();