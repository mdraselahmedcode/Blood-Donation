<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . '/config/db.php';

// Redirect if admin is logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

// Redirect if not logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/user/login.php');
    exit;
}



// Get session variables
$userId = $_SESSION['user_id'];
$userType = $_SESSION['user_type'] ?? 'user';
$name = $_SESSION['user_name'] ?? 'User';

// HARD-CODED values (replace with actual DB queries later)
$requestCount = 3;     // example: user has made 3 blood requests
$donationCount = 5;    // example: donor has made 5 donations
?>

<?php include BASE_PATH . '/includes/header.php'; ?>
<?php include BASE_PATH . '/includes/sidebar.php'; ?>

<head>
  <link rel="stylesheet" href="<?= BASE_URL . '/user/assets/css/dashboard.css' ?>">
</head>

<main class="content" >
    <h2>Welcome, <?= htmlspecialchars($name) ?>!</h2>

    <div class="dashboard-cards">
        <?php if ($userType === 'donor'): ?>
            <a href="#" class="card">
                <h3>My Donations</h3>
                <p><?= $donationCount ?></p>
            </a>

            <a href="#" class="card">
                <h3>Incoming Requests</h3>
                <p>2</p>
            </a>

            <a href="<?= BASE_URL . '/user/donor/profile.php' ?>" class="card">
                <h3>Profile</h3>
                <p>View / Update</p>
            </a>
        <?php elseif ($userType === 'user'): ?>
            <a href="#" class="card">
                <h3>My Blood Requests</h3>
                <p><?= $requestCount ?></p>
            </a>

            <a href="<?= BASE_URL . '/user/receiver/find_donor.php' ?>" class="card">
                <h3>Find Donor</h3>
                <p>Search Nearby</p>
            </a>
            <a href="#" class="card">
                <h3>Become a Donor</h3>
                <p>Register</p>
            </a>
        <?php endif; ?>
    </div>
</main>

<?php include BASE_PATH . '/includes/footer.php'; ?>