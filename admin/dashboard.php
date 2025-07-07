<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . '/config/db.php';  // include DB connection

// Redirect if user or donor is logged in
if (!empty($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/user/dashboard.php');
    exit;
}

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

// Query total users
$normalUserCount = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($res && $row = $res->fetch_assoc()) {
    $normalUserCount = (int)$row['total'];
}

// Query total users
$adminCount = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM admins");
if ($res && $row = $res->fetch_assoc()) {
    $adminCount = (int)$row['total'];
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
    <h2 style="display: flex; align-items: center; gap: 10px;">
        Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>!

        <?php
        // Assume is_protected is set in session, else 0
        $is_protected = $_SESSION['is_protected'] ?? 0;

        if (!empty($_SESSION['super_admin']) && $_SESSION['super_admin'] == 1):
            if ($is_protected == 1):
                // Badge for super admin + protected
        ?>
                <span style="
                display: inline-flex;
                align-items: center;
                background: linear-gradient(90deg, #4caf50 60%, #81c784 100%);
                color: #fff;
                font-size: 0.85em;
                padding: 3px 14px 3px 8px;
                border-radius: 999px;
                margin-left: 8px;
                box-shadow: 0 0 10px 3px rgba(76, 175, 80, 0.7);
                font-weight: 600;
                letter-spacing: 0.5px;
                gap: 6px;
            ">
                    <span style="font-size:1.1em; margin-right:3px;">👑</span> Super Admin
                    <span style="font-size: 0.75em; font-weight: 500; opacity: 0.9; margin-left: 6px;">(Protected)</span>
                </span>
            <?php else:
                // Default super admin badge
            ?>
                <span style="
                display: inline-flex;
                align-items: center;
                background: linear-gradient(90deg, #ff9800 60%, #ffc107 100%);
                color: #fff;
                font-size: 0.85em;
                padding: 3px 14px 3px 8px;
                border-radius: 999px;
                margin-left: 8px;
                box-shadow: 0 2px 8px rgba(255,152,0,0.10);
                font-weight: 600;
                letter-spacing: 0.5px;
                gap: 6px;
            ">
                    <span style="font-size:1.1em; margin-right:3px;">👑</span> Super Admin
                </span>
        <?php
            endif;
        endif;
        ?>
    </h2>


    <div class="dashboard-cards">
        <a href="<?= BASE_URL ?>/admin/blood_groups/index.php" class="card">
            <h3>Total Blood Group</h3>
            <p><?= $bloodGroupCount ?></p>
        </a>

        <a href="<?= BASE_URL ?>/admin/cities/index.php" class="card">
            <h3>Total City</h3>
            <p><?= $cityCount ?></p>
        </a>

        <a href="<?= BASE_URL ?>/admin/donors/index.php" class="card">
            <h3>Total Donors</h3>
            <p><?= $donorCount ?></p>
        </a>

        <a href="<?= BASE_URL ?>/admin/users/index.php" class="card">
            <h3>Total Users</h3>
            <p><?= $normalUserCount ?></p>
        </a>

        <?php if (!empty($_SESSION['super_admin']) && $_SESSION['super_admin'] == 1): ?>
            <a href="<?= BASE_URL ?>/admin/admins/index.php" class="card">
                <h3>Admins</h3>
                <p><?= $adminCount ?></p>
            </a>
        <?php endif; ?>

        <a href="#" class="card">
            <h3>Pending Approvals</h3>
            <p>12</p>
        </a>

        <a href="#" class="card">
            <h3>Blood Requests</h3>
            <p>87</p> <!-- You can make this dynamic similarly -->
        </a>
    </div>
</main>

<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>