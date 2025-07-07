<?php
session_start();
// Set proper 404 header
header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found | BloodConnect</title>
    <link rel="stylesheet" href="/blood-donate/assets/css/error.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="error-container">
        <div class="error-content">
            <div class="error-code">404</div>
            <h1 class="error-title">Page Not Found</h1>
            
            <?php if (isset($_SESSION['admin_logged_in'])) : ?>
                <!-- Admin View -->
                <p class="error-message">
                    <i class="fas fa-exclamation-triangle"></i> The requested admin resource was not found.
                </p>
                <div class="error-actions">
                    <a href="/blood-donate/admin/dashboard" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                    </a>
                    <a href="/blood-donate/admin/php_files/logout_admin_handler.php" class="btn btn-secondary">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
                
            <?php elseif (isset($_SESSION['user_logged_in'])) : ?>
                <!-- Logged-in User View -->
                <p class="error-message">
                    <?php if ($_SESSION['is_donor']) : ?>
                        <i class="fas fa-hand-holding-heart"></i> Dear donor, the page you requested isn't available.
                    <?php else : ?>
                        <i class="fas fa-user"></i> The page you're looking for doesn't exist.
                    <?php endif; ?>
                </p>
                <div class="error-actions">
                    <a href="/blood-donate/user/dashboard" class="btn btn-primary">
                        <i class="fas fa-home"></i> My Dashboard
                    </a>
                    <?php if ($_SESSION['is_donor']) : ?>
                        <a href="/blood-donate/user/donor/profile" class="btn btn-secondary">
                            <i class="fas fa-user-edit"></i> Donor Profile
                        </a>
                    <?php else : ?>
                        <a href="/blood-donate/user/receiver/find_donor" class="btn btn-secondary">
                            <i class="fas fa-search"></i> Find Donor
                        </a>
                    <?php endif; ?>
                </div>
                
            <?php else : ?>
                <!-- Guest View -->
                <p class="error-message">
                    The page you're looking for doesn't exist or has been moved.
                </p>
                <div class="error-actions">
                    <a href="/blood-donate/" class="btn btn-primary">
                        <i class="fas fa-home"></i> Return Home
                    </a>
                    <div class="auth-buttons">
                        <a href="/blood-donate/user/login" class="btn btn-secondary">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="/blood-donate/user/signup" class="btn btn-secondary">
                            <i class="fas fa-user-plus"></i> Sign Up
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</body>
</html>