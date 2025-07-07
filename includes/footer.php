<?php
require_once __DIR__ . '/../config/config.php';

$isLoggedInUser = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>

<head>
    <style>
        .footer {
            background: linear-gradient(135deg, #b71c1c 0%, #7f0000 100%);
            color: #fff;
            padding: 50px 0 0;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
        }

        .footer-simple {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-main {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .footer-brand h3 {
            font-size: 28px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .blood-icon {
            color: #ffcdd2;
        }

        .footer-motto {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .footer-cta {
            font-weight: 600;
            font-size: 18px;
            color: #ffcdd2;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .footer-heading {
            font-size: 18px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: #ffcdd2;
        }

        .footer-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-list a {
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.8);
        }

        .footer-list a:hover {
            color: #fff;
            transform: translateX(5px);
        }

        .footer-contact-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .footer-contact-list li {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        .newsletter p {
            margin-bottom: 10px;
            font-size: 15px;
        }

        .newsletter-form {
            display: flex;
        }

        .newsletter-form input {
            flex: 1;
            padding: 10px 15px;
            border: none;
            border-radius: 4px 0 0 4px;
            font-size: 14px;
        }

        .newsletter-form button {
            background: #ff5252;
            color: white;
            border: none;
            padding: 0 15px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            transition: background 0.3s;
        }

        .newsletter-form button:hover {
            background: #ff6e6e;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 0;
            margin-top: 40px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .footer-copyright {
            font-size: 14px;
            opacity: 0.8;
        }

        .footer-legal {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .footer-legal a {
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            transition: color 0.3s;
        }

        .footer-legal a:hover {
            color: #fff;
        }

        .footer-legal span {
            opacity: 0.5;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .footer-main {
                gap: 30px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>



<footer class="footer">
    <?php if ($isLoggedInUser): ?>
        <div class="footer-simple">
            &copy; 2025 BloodCare. All rights reserved.
        </div>
    <?php else: ?>
        <div class="footer-content">
            <div class="footer-main">
                <div class="footer-brand">
                    <h3><i class="fas fa-tint blood-icon"></i> BloodCare</h3>
                    <p class="footer-motto">Saving lives through voluntary blood donation</p>
                    <p class="footer-cta">Join us and be a hero today!</p>
                </div>

                <div class="footer-grid">
                    <div class="footer-section">
                        <h4 class="footer-heading">Quick Links</h4>
                        <ul class="footer-list">
                            <li><a href="<?= BASE_URL . '/index.php' ?>"><i class="fas fa-chevron-right"></i> Home</a></li>
                            <li><a href="<?= BASE_URL . '/user/signup_only_donor.php' ?>"><i class="fas fa-chevron-right"></i> Donate</a></li>
                            <li><a href="<?= BASE_URL . '/user/login.php' ?>"><i class="fas fa-chevron-right"></i> Find Blood</a></li>
                            <li><a href="#about"><i class="fas fa-chevron-right"></i> About Us</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h4 class="footer-heading">Contact</h4>
                        <ul class="footer-contact-list">
                            <li><i class="fas fa-envelope"></i> mdraselahmed.code@gmail.com</li>
                            <li><i class="fas fa-phone"></i> +8801929951023</li>
                            <li><i class="fas fa-map-marker-alt"></i> Dhaka, Bangladesh</li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h4 class="footer-heading">Follow Us</h4>
                        <div class="social-links">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f" style="color: white"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter" style="color: white"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram" style="color: white"></i></a>
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in" style="color: white"></i></a>
                        </div>
                        <div class="newsletter">
                            <p>Subscribe to our newsletter</p>
                            <form class="newsletter-form">
                                <input type="email" placeholder="Your email">
                                <button type="submit"><i class="fas fa-paper-plane"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-copyright">&copy; 2025 BloodCare. All rights reserved.</div>
                <div class="footer-legal">
                    <a href="#privacy">Privacy Policy</a>
                    <span>•</span>
                    <a href="#terms">Terms of Service</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</footer>