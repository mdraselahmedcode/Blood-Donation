<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . '/config/db.php';  // include DB connection

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}

// Query total donors
$donorCount = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM donors");
if ($res && $row = $res->fetch_assoc()) {
    $donorCount = (int)$row['total'];
}

// Query total blood groups
$bloodGroupCount = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM blood_groups");
if ($res && $row = $res->fetch_assoc()) {
    $bloodGroupCount = (int)$row['total'];
}

// Query total cities
$cityCount = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM cities");
if ($res && $row = $res->fetch_assoc()) {
    $cityCount = (int)$row['total'];
}
?>

<?php require_once BASE_PATH . '/admin/includes/header_admin.php'; ?>
<?php require_once BASE_PATH . '/admin/includes/sidebar_admin.php'; ?>

<main class="content">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>!</h2>

    <div class="dashboard-cards">
        <a href="<?= BASE_URL ?>/admin/donors/index.php" class="card">
            <h3>Total Donors</h3>
            <p><?= $donorCount ?></p>
        </a>

        <a href="<?= BASE_URL ?>/admin/requests.php" class="card">
            <h3>Blood Requests</h3>
            <p>87</p> <!-- You can make this dynamic similarly -->
        </a>

        <a href="<?= BASE_URL ?>/admin/blood_groups/index.php" class="card">
            <h3>Total Blood Group</h3>
            <p><?= $bloodGroupCount ?></p>
        </a>

        <a href="<?= BASE_URL ?>/admin/cities/index.php" class="card">
            <h3>Total City</h3>
            <p><?= $cityCount ?></p>
        </a>

        <a href="<?= BASE_URL ?>/admin/users.php" class="card">
            <h3>Total Users</h3>
            <p>300</p> <!-- Also can be made dynamic -->
        </a>

        <a href="<?= BASE_URL ?>/admin/pending_approvals.php" class="card">
            <h3>Pending Approvals</h3>
            <p>12</p>
        </a>

        <a href="<?= BASE_URL ?>/admin/admins.php" class="card">
            <h3>Admins</h3>
            <p>3</p>
        </a>
    </div>
</main>

<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>
