<?php
require_once __DIR__ . '/../config/config.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation</title>

    <!-- main.css -->
    <link rel="stylesheet" href="<?= BASE_URL . '/assets/css/main.css' ?>">
    <!-- header styles-->
    <link rel="stylesheet" href="<?= BASE_URL . '/assets/css/header.css' ?>">
    <!-- footer styles -->
    <link rel="stylesheet" href="<?= BASE_URL . '/assets/css/footer.css' ?>">


</head>

<body>

    <header class="navbar">
        <div class="logo">BloodCare</div>
        <nav class="nav-links">
            <a href="<?= BASE_URL . '/index.php' ?>">Home</a>
            <a href="#">About Us</a>
            <a href="#">Donate</a>
            <a href="#">Find Blood</a>
            <a href="#">Contact</a>
            <a href="<?= BASE_URL . '/admin/login.php' ?>">Admin</a>
        </nav>
        <button class="cta-button" onclick="window.location.href='<?= BASE_URL . '/register.php' ?>'">
            Become a Donor
        </button>
    </header>