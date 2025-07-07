<?php
require_once __DIR__ . '/../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BloodCare - <?= isset($_SESSION['user_logged_in']) ? 'Dashboard' : 'Welcome' ?></title>

  <!-- Shared Styles -->
  <link rel="stylesheet" href="<?= BASE_URL . '/assets/css/main.css' ?>">
  <link rel="stylesheet" href="<?= BASE_URL . '/assets/css/header.css' ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

  <header class="navbar">
    <!-- <div class="logo">BloodCare</div> -->
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
      <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
        <a href="<?= BASE_URL . '/user/dashboard.php' ?>">Dashboard</a>

        <?php if (!empty($_SESSION['is_donor'])): ?>
          <!-- Donor-specific links -->
          <a href="#">My Donations</a>
          <a href="#">Incoming Requests</a>
        <?php else: ?>
          <!-- Normal user-specific links -->
          <a href="#">Request Blood</a>
          <a href="<?= BASE_URL . '/user/receiver/find_donor.php' ?>">Find Donors</a>
        <?php endif; ?>

        <?php
        $userType = $_SESSION['user_type'] ?? '';

        switch ($userType) {
          case 'donor':
            $profileLink = BASE_URL . '/user/donor/profile.php';
            break;
          case 'user':
            $profileLink = BASE_URL . '/user/receiver/profile.php';
            break;
          default:
            $profileLink = '#'; // fallback or redirect to a generic profile
            break;
        }
        ?>
        <a href="<?= $profileLink ?>">Profile</a>


      <?php else: ?>
        <!-- Guest navigation -->
        <a href="<?= BASE_URL . '/index.php' ?>">Home</a>
        <a href="<?= BASE_URL . '/user/login.php' ?>">Donate</a>
        <a href="<?= BASE_URL . '/user/login.php' ?>">Find Blood</a>
        <a href="<?= BASE_URL . '/contact.php' ?>">Contact</a>
        <a href="<?= BASE_URL . '/about_us.php' ?>">About Us</a>
        <a href="<?= BASE_URL . '/admin/login.php' ?>">Admin Login</a>
        <a href="<?= BASE_URL . '/user/login.php' ?>">Login</a>
        <a href="<?= BASE_URL . '/user/signup.php' ?>">Sign Up</a>
      <?php endif; ?>
    </nav>

    <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
      <button class="cta-button" onclick="window.location.href='<?= BASE_URL ?>/user/php_files/logout_handler.php'">
        Logout
      </button>
    <?php else: ?>
      <button class="cta-button" onclick="window.location.href='<?= BASE_URL ?>/user/signup_only_donor.php'">
        Become a Donor
      </button>
    <?php endif; ?>
  </header>

  <!-- Local jQuery -->
  <script src="<?= BASE_URL ?>/vendor/jquery/jquery.min.js"></script>