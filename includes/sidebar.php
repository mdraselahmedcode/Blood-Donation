<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/config.php';
?>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

<style>
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        height: 100vh;
        background-color: #c62828;
        color: #fff;
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        z-index: 1000;
        overflow: hidden;
    }

    .site-logo {
        padding: 20px 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        margin-bottom: 5px;
    }

    .logo-link {
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        font-family: 'Segoe UI', sans-serif;
        transition: all 0.3s ease;
    }

    .logo-icon {
        font-size: 2rem;
        margin-right: 12px;
        animation: pulse 2s infinite;
    }

    .logo-text {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .user-badge {
        font-size: 0.75rem;
        background: rgba(255, 255, 255, 0.15);
        padding: 3px 8px;
        border-radius: 12px;
        margin-left: 10px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0 15px;
        margin: 0;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 15px;
        font-weight: 500;
        text-decoration: none;
        border-radius: 6px;
        margin-bottom: 5px;
        transition: all 0.3s ease;
    }

    .sidebar-link i {
        font-size: 1.1rem;
        margin-right: 15px;
        width: 24px;
        text-align: center;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .sidebar-footer {
        margin-top: auto;
        margin-bottom: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 10px;
    }

    .logout-link:hover {
        background-color: #9e0000;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 70px;
        }

        .logo-text,
        .user-badge,
        .link-text {
            display: none;
        }

        .sidebar-link {
            justify-content: center;
            padding: 16px 10px;
        }

        .logo-icon {
            margin-right: 0;
            font-size: 2.2rem;
        }

        .content {
            margin-left: 70px;
        }
    }

    .content {
        margin-left: 260px;
        padding: 30px;
        transition: margin-left 0.3s ease;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }
</style>

<!-- <aside class="sidebar">
    <div class="site-logo">
        <a href="<?= BASE_URL ?>/user/dashboard.php" class="logo-link">
            <span class="logo-icon">🩸</span>
            <span class="logo-text">BloodCare</span>
            <span class="user-badge">
                <?= ($_SESSION['user_type'] ?? 'user') === 'donor' ? 'Donor' : 'User' ?>
            </span>
        </a>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="<?= BASE_URL ?>/user/dashboard.php" class="sidebar-link">
                <i class="bi bi-house-door-fill"></i>
                <span class="link-text">Dashboard</span>
            </a>
        </li>

        <?php if (($_SESSION['user_type'] ?? 'user') === 'donor'): ?>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-droplet-half"></i>
                    <span class="link-text">My Donations</span>
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="<?= BASE_URL . '/user/receiver/find_donor.php' ?>" class="sidebar-link">
                    <i class="bi bi-search-heart"></i>
                    <span class="link-text">Find Donor</span>
                </a>
            </li>
            <li>
                <a href="#" class="sidebar-link">
                    <i class="bi bi-journal-medical"></i>
                    <span class="link-text">Request Blood</span>
                </a>
            </li>
        <?php endif; ?>

        <li>
            <a href="<?php
                        if (($_SESSION['user_type'] ?? 'user') === 'donor') {
                            echo BASE_URL . '/user/donor/profile.php';
                        } else {
                            echo BASE_URL . '/user/receiver/profile.php';
                        }
                        ?>" class="sidebar-link">
                <i class="bi bi-person-vcard-fill"></i>
                <span class="link-text">Profile</span>
            </a>
        </li>

        <li class="sidebar-footer">
            <a href="<?= BASE_URL ?>/user/php_files/logout_handler.php" class="sidebar-link logout-link">
                <i class="bi bi-box-arrow-right"></i>
                <span class="link-text">Logout</span>
            </a>
        </li>
    </ul>
</aside> -->

<aside class="sidebar">
    <div class="site-logo">
        <a href="<?= BASE_URL ?>/user/dashboard.php" class="logo-link">
            <span class="logo-icon">🩸</span>
            <span class="logo-text">BloodCare</span>
            <span class="user-badge">
                <?= ($_SESSION['user_type'] ?? 'user') === 'donor' ? 'Donor' : 'User' ?>
            </span>
        </a>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="<?= BASE_URL ?>/user/dashboard.php"
               class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/user/dashboard.php') !== false ? 'active' : '' ?>">
                <i class="bi bi-house-door-fill"></i>
                <span class="link-text">Dashboard</span>
            </a>
        </li>

        <?php if (($_SESSION['user_type'] ?? 'user') === 'donor'): ?>
            <li>
                <a href="#"
                   class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/user/donor/my_donations.php') !== false ? 'active' : '' ?>">
                    <i class="bi bi-droplet-half"></i>
                    <span class="link-text">My Donations</span>
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="<?= BASE_URL . '/user/receiver/find_donor.php' ?>"
                   class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/user/receiver/find_donor.php') !== false ? 'active' : '' ?>">
                    <i class="bi bi-search-heart"></i>
                    <span class="link-text">Find Donor</span>
                </a>
            </li>
            <li>
                <a href="#"
                   class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/user/receiver/request_blood.php') !== false ? 'active' : '' ?>">
                    <i class="bi bi-journal-medical"></i>
                    <span class="link-text">Request Blood</span>
                </a>
            </li>
        <?php endif; ?>

        <li>
            <a href="<?php
                        if (($_SESSION['user_type'] ?? 'user') === 'donor') {
                            echo BASE_URL . '/user/donor/profile.php';
                        } else {
                            echo BASE_URL . '/user/receiver/profile.php';
                        }
                        ?>"
               class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/user/donor/profile.php') !== false || strpos($_SERVER['REQUEST_URI'], '/user/receiver/profile.php') !== false ? 'active' : '' ?>">
                <i class="bi bi-person-vcard-fill"></i>
                <span class="link-text">Profile</span>
            </a>
        </li>

        <li class="sidebar-footer">
            <a href="<?= BASE_URL ?>/user/php_files/logout_handler.php" class="sidebar-link logout-link">
                <i class="bi bi-box-arrow-right"></i>
                <span class="link-text">Logout</span>
            </a>
        </li>
    </ul>
</aside>

