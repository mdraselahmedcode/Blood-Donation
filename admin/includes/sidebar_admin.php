<?php 
  require_once __DIR__ . '/../../config/config.php';
?>
<head>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Internal Sidebar Styles -->
    <style>
        /* Sidebar container */
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
            transition: all 0.3s ease;
            overflow: hidden;
        }

        /* Sidebar header */
        .sidebar-header {
            padding: 20px 25px;
            border-bottom: 2px solid rgb(201, 66, 66);
            margin-bottom: 10px;
            position: relative;
        }

        

        .sidebar-brand {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
        }

        /* Sidebar menu */
        .sidebar-menu {
            list-style: none;
            padding: 0 15px;
            margin: 0;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        /* Sidebar links */
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 6px;
            margin-bottom: 5px;
            position: relative;
        }

        .sidebar-link i {
            font-size: 1.1rem;
            margin-right: 15px;
            width: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .link-text {
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        /* Hover & Active State */
        .sidebar-link:hover,
        .sidebar-link.active {
            background-color: rgba(183, 28, 28, 0.8);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-link:hover i,
        .sidebar-link.active i {
            transform: scale(1.1);
        }

        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            font-weight: 600;
        }

        /* Logout button special styling */
        .sidebar-footer {
            margin-top: auto;
            margin-bottom: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 10px;
        }

        .logout-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .logout-link:hover {
            background-color: #9e0000;
            color: #fff;
        }

        /* Responsive (mobile) */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar-header {
                display: none;
            }
            
            .link-text {
                display: none;
            }
            
            .sidebar-link {
                justify-content: center;
                padding: 16px 10px;
                margin-bottom: 0;
            }
            
            .sidebar-link i {
                margin-right: 0;
                font-size: 1.3rem;
            }
            
            .sidebar-link:hover,
            .sidebar-link.active {
                transform: none;
                background-color: #b71c1c;
            }
            
            .content {
                margin-left: 70px;
            }
        }

        /* Content shift for desktop sidebar */
        .content {
            margin-left: 260px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }


        /* Logo Container */
.site-logo {
    padding: 20px 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    margin-bottom: 5px;
}

/* Logo Link */
.logo-link {
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    font-family: 'Segoe UI', system-ui, sans-serif;
    transition: all 0.3s ease;
}

/* Blood Drop Icon */
.logo-icon {
    font-size: 2rem;
    margin-right: 12px;
    animation: pulse 2s infinite;
    transform-origin: center;
}

/* Text Part */
.logo-text {
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

/* Admin Badge */
.admin-badge {
    font-size: 0.75rem;
    background: rgba(255,255,255,0.15);
    padding: 3px 8px;
    border-radius: 12px;
    margin-left: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Animation */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Hover Effects */
.logo-link:hover {
    opacity: 0.9;
}
.logo-link:hover .logo-icon {
    animation: none;
    transform: scale(1.1);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .site-logo {
        padding: 15px 10px;
    }
    .logo-text, .admin-badge {
        display: none;
    }
    .logo-icon {
        margin-right: 0;
        font-size: 2.2rem;
    }
}
    </style>
</head>
<aside class="sidebar">
  <div class="site-logo">
    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="logo-link">
      <span class="logo-icon">🩸</span>
      <span class="logo-text">BloodCare</span>
      <span class="admin-badge">Admin</span>
    </a>
  </div>
  <!-- Sidebar header -->
  <!-- <div class="sidebar-header">
    <h3 class="sidebar-brand">Admin Panel</h3>
  </div> -->
  
  <ul class="sidebar-menu">
    <li><a href="<?= BASE_URL ?>/admin/dashboard.php" class="sidebar-link"><i class="bi bi-speedometer2"></i><span class="link-text">Dashboard</span></a></li>
    <li><a href="<?= BASE_URL ?>/admin/blood_groups/index.php" class="sidebar-link"><i class="bi bi-droplet"></i><span class="link-text">Blood Groups</span></a></li>
    <li><a href="<?= BASE_URL ?>/admin/donors/index.php" class="sidebar-link"><i class="bi bi-people-fill"></i><span class="link-text">Donors</span></a></li>
    <li><a href="<?= BASE_URL ?>/admin/cities/index.php" class="sidebar-link"><i class="bi bi-building"></i><span class="link-text">Cities</span></a></li>
    <li><a href="<?= BASE_URL ?>/admin/requests.php" class="sidebar-link"><i class="bi bi-inbox-fill"></i><span class="link-text">Requests</span></a></li>
    <li><a href="<?= BASE_URL ?>/admin/settings.php" class="sidebar-link"><i class="bi bi-gear-fill"></i><span class="link-text">Settings</span></a></li>
    <li class="sidebar-footer"><a href="<?= BASE_URL ?>/admin/php_files/logout_admin_handler.php" class="sidebar-link logout-link"><i class="bi bi-box-arrow-right"></i><span class="link-text">Logout</span></a></li>
  </ul>
</aside>