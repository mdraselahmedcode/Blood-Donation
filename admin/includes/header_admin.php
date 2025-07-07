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
        <!-- <div class="logo"><?= isset($_SESSION['admin_logged_in']) ? ' ' : 'BloodCare' ?></div> -->
        <div class="logo">
            <?php if (isset($_SESSION['admin_logged_in'])): ?>
                <!-- Show nothing or keep it blank if admin is logged in -->
            <?php else: ?>
                <a href="<?= BASE_URL . '/index.php' ?>" style="text-decoration: none; color: inherit;">
                    BloodCare
                </a>
            <?php endif; ?>
        </div>


        <nav class="nav-links">
            <?php if (isset($_SESSION['admin_logged_in'])): ?>
                <a href="<?= BASE_URL . '/admin/dashboard.php' ?>">Dashboard</a>
                <a href="<?= BASE_URL . '/admin/blood_groups/index.php' ?>">Blood Groups</a>
                <a href="<?= BASE_URL . '/admin/donors/index.php' ?>">Donors</a>
                <a href="<?= BASE_URL . '/admin/users/index.php' ?>">Users</a>
                <a href="<?= BASE_URL . '/admin/cities/index.php' ?>">Cities</a>
                <a href="<?= BASE_URL . '/admin/profile/index.php' ?>">Your Profile</a>
                <?php if (!empty($_SESSION['super_admin']) && $_SESSION['super_admin'] == 1): ?>
                    <a href="<?= BASE_URL . '/admin/admins/index.php' ?>">Admins</a>
                <?php endif; ?>
                <a href="#">Requests</a>
                <a href="#">Settings</a>
            <?php else: ?>
                <a href="<?= BASE_URL . '/index.php' ?>">Home</a>
                <a href="<?= BASE_URL . '/user/signup.php' ?>">Donate</a>
                <a href="<?= BASE_URL . '/user/signup.php' ?>">Find Blood</a>
                <a href="<?= BASE_URL . '/contact.php' ?>">Contact</a>
                <a href="<?= BASE_URL . '/about_us.php' ?>">About Us</a>
                <a href="<?= BASE_URL . '/admin/login.php' ?>">Admin Login</a>
                <a href="<?= BASE_URL . '/user/login.php' ?>">Login</a>
                <a href="<?= BASE_URL . '/user/signup.php' ?>">Sign Up</a>
            <?php endif; ?>
        </nav>


        <?php if (isset($_SESSION['admin_logged_in'])): ?>
            <button class="cta-button" onclick="window.location.href='<?= BASE_URL . '/admin/php_files/logout_admin_handler.php' ?>'">
                Logout
            </button>
        <?php else: ?>
            <button class="cta-button" onclick="window.location.href='<?= BASE_URL . '/user/signup_only_donor.php' ?>'">
                Become a Donor
            </button>

        <?php endif; ?>
    </header>


    <!-- Local jQuery -->
    <script src="<?= BASE_URL ?>/vendor/jquery/jquery.min.js"></script>