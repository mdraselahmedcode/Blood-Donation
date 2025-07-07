<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

// Ensure logged-in and is a super admin
if (
    !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ||
    empty($_SESSION['super_admin']) || $_SESSION['super_admin'] != 1
) {
    echo json_encode(['success' => false, 'message' => '⛔ Unauthorized access.']);
    exit;
}

// Get logged-in admin info
$currentAdminId = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT is_protected FROM admins WHERE id = ?");
$stmt->bind_param("i", $currentAdminId);
$stmt->execute();
$result = $stmt->get_result();
$currentAdmin = $result->fetch_assoc();
$stmt->close();

if (!$currentAdmin || $currentAdmin['is_protected'] != 1) {
    echo json_encode(['success' => false, 'message' => '⛔ Action not allowed.']);
    exit;
}

// Validate input
$adminIdToDelete = intval($_POST['id'] ?? 0);
if ($adminIdToDelete <= 0) {
    echo json_encode(['success' => false, 'message' => '⚠️ Invalid admin ID.']);
    exit;
}

// Prevent deleting yourself
if ($adminIdToDelete == $currentAdminId) {
    echo json_encode(['success' => false, 'message' => '❌ You cannot delete yourself.']);
    exit;
}

// Fetch the target admin's info
$stmt = $conn->prepare("SELECT super_admin, is_protected FROM admins WHERE id = ?");
$stmt->bind_param("i", $adminIdToDelete);
$stmt->execute();
$result = $stmt->get_result();
$targetAdmin = $result->fetch_assoc();
$stmt->close();

if (!$targetAdmin) {
    echo json_encode(['success' => false, 'message' => '❌ Admin not found.']);
    exit;
}

// Prevent deleting a protected super admin
if ($targetAdmin['super_admin'] == 1 && $targetAdmin['is_protected'] == 1) {
    echo json_encode(['success' => false, 'message' => '⛔ Cannot delete a protected super admin.']);
    exit;
}

// Delete the admin
$stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
$stmt->bind_param("i", $adminIdToDelete);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => '✅ Admin deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => '❌ Deletion failed: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
