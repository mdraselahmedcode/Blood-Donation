<?php
require_once __DIR__ . '/../../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blood Donation<?= isset($_SESSION['admin_logged_in']) ? ' - Admin Panel' : '' ?></title>

    <!-- Main Styles -->
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/main.css' ?>">
    <!-- header styles -->
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/header_admin.css' ?>">
    <!-- footer styles -->
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/footer_admin.css' ?>">
    <!-- dashboard_admin.css -->
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/dashboard_admin.css' ?>">

</head>

<body>

    <header class="navbar">
        <div class="logo" ><?= isset($_SESSION['admin_logged_in']) ? ' ' : 'BloodCare' ?></div>

        <nav class="nav-links">
            <?php if (isset($_SESSION['admin_logged_in'])): ?>
                <a href="<?= BASE_URL . '/admin/dashboard.php' ?>">Dashboard</a>
                <a href="<?= BASE_URL . '/admin/donors/index.php' ?>">Donors</a>
                <a href="<?= BASE_URL . '/admin/requests.php' ?>">Requests</a>
                <a href="<?= BASE_URL . '/admin/settings.php' ?>">Settings</a>
                <a href="<?= BASE_URL . '/admin/profile/index.php' ?>">Profile</a>
            <?php else: ?>
                <a href="<?= BASE_URL . '/index.php' ?>">Home</a>
                <a href="#">About Us</a>
                <a href="#">Donate</a>
                <a href="#">Find Blood</a>
                <a href="#">Contact</a>
                <a href="<?= BASE_URL . '/user/login.php' ?>">Login</a>
                <a href="<?= BASE_URL . '/user/signup.php' ?>">Sign Up</a>
                <a href="<?= BASE_URL . '/admin/login.php' ?>">Admin Login</a>
            <?php endif; ?>
        </nav>

        <?php if (isset($_SESSION['admin_logged_in'])): ?>
            <button class="cta-button" onclick="window.location.href='<?= BASE_URL . '/admin/php_files/logout_admin_handler.php' ?>'">
                Logout
            </button>
        <?php else: ?>
            <button class="cta-button" onclick="window.location.href='<?= BASE_URL . '/register.php' ?>'">
                Become a Donor
            </button>

        <?php endif; ?>
    </header>


    <!-- Local jQuery -->
    <script src="<?= BASE_URL ?>/vendor/jquery/jquery.min.js"></script>