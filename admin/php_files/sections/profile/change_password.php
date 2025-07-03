<?php
session_start();
require_once __DIR__ . '/../../../../config/config.php';
require_once BASE_PATH . '/config/db.php';

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['admin_id'])) {
    $response['message'] = 'Unauthorized.';
    echo json_encode($response);
    exit;
}

$adminId = $_SESSION['admin_id'];
$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!$current || !$new || !$confirm) {
    $response['message'] = 'All fields are required.';
} elseif ($new !== $confirm) {
    $response['message'] = 'New passwords do not match.';
} else {
    $stmt = $conn->prepare("SELECT password FROM admins WHERE id = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    if (!$admin || !password_verify($current, $admin['password'])) {
        $response['message'] = 'Current password is incorrect.';
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $adminId);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Password changed successfully.';
        } else {
            $response['message'] = 'Failed to update password.';
        }
        $stmt->close();
    }
}

echo json_encode($response);
