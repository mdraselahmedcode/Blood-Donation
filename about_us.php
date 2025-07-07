<?php
session_start();
include __DIR__ . '/config/config.php';
require_once BASE_PATH . '/includes/header.php';
require_once BASE_PATH . '/config/db.php';


$donorCount = 0;
$result = $conn->query("SELECT COUNT(*) as total FROM donors WHERE status = 'Active'");
if ($result && $row = $result->fetch_assoc()) {
    $donorCount = $row['total'];
}

?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL . '/assets/css/about_us.css' ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<main class="about-page">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <h1>About BloodCare</h1>
            <p>Connecting donors with those in need since 2023</p>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="container">
            <div class="mission-content">
                <div class="mission-text">
                    <h2>Our Mission</h2>
                    <p>At BloodCare, we're dedicated to creating a reliable network between blood donors and recipients across Bangladesh. Our platform bridges the gap between voluntary donors and patients in critical need of blood transfusions.</p>
                    <p>Every day, countless lives are saved through blood donations. We make this process simpler, faster, and more efficient through our technology-driven solution.</p>
                    <div class="mission-stats">
                        <div class="stat-item">
                            <div class="stat-number" data-count="130">0</div>
                            <div class="stat-label">Lives Impacted</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-count="<?= $donorCount ?>">0</div>
                            <div class="stat-label">Registered Donors</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number" data-count="3">0</div>
                            <div class="stat-label">Partner Hospitals</div>
                        </div>
                    </div>
                </div>
                <div class="mission-image">
                    <img src="<?= BASE_URL ?>/assets/images/heart.jpg" alt="Blood donation saving lives">
                </div>
            </div>
        </div>
    </section>

    <!-- How We Help Section -->
    <section class="help-section">
        <div class="container">
            <h2>How We Help The Community</h2>
            <div class="help-grid">
                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h3>For Donors</h3>
                    <ul>
                        <li>Easy registration process</li>
                        <li>Notifications when your blood type is needed</li>
                        <li>Track your donation history</li>
                        <li>Receive health tips and updates</li>
                    </ul>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <h3>For Patients</h3>
                    <ul>
                        <li>Quick access to compatible donors</li>
                        <li>Search by location and blood type</li>
                        <li>Direct contact with donors</li>
                        <li>Emergency request system</li>
                    </ul>
                </div>

                <div class="help-card">
                    <div class="help-icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <h3>For Hospitals</h3>
                    <ul>
                        <li>Verified donor database</li>
                        <li>Real-time availability status</li>
                        <li>Reduced shortage situations</li>
                        <li>Streamlined coordination</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <h2>Our Team</h2>
            <p class="team-subtitle">The passionate individuals behind BloodCare</p>

            <div class="team-grid">
                <div class="team-member">
                    <div class="member-image">
                        <img src="<?= BASE_URL ?>/assets/images/girl.jpg" alt="Team member">
                    </div>
                    <h3>Dr. Ayesha Rahman</h3>
                    <p class="position">Medical Director</p>
                    <p class="bio">Hematology specialist with 15 years experience in blood bank management.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>

                <div class="team-member">
                    <div class="member-image">
                        <img src="<?= BASE_URL ?>/assets/images/team-member2.jpeg" alt="Team member">
                    </div>
                    <h3>Md Rasel Ahmed</h3>
                    <p class="position">Founder & Developer</p>
                    <p class="bio">Software engineer passionate about creating life-saving solutions.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://github.com/mdraselahmedcode"><i class="fab fa-github"></i></a>
                    </div>
                </div>

                <div class="team-member">
                    <div class="member-image">
                        <img src="<?= BASE_URL ?>/assets/images/girl_2.jpg" alt="Team member">
                    </div>
                    <h3>Fatima Khan</h3>
                    <p class="position">Community Manager</p>
                    <p class="bio">Organizes donor drives and community awareness programs.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="about-cta">
        <div class="container">
            <h2>Ready to Make a Difference?</h2>
            <p>Join our growing community of life-savers today</p>
            <div class="cta-buttons">
                <a href="<?= BASE_URL ?>/user/signup.php" class="cta-button">Become a Donor</a>
                <a href="<?= BASE_URL ?>/contact.php" class="cta-secondary">Contact Us</a>
            </div>
        </div>
    </section>
</main>

<?php
require_once BASE_PATH . '/includes/footer.php';
?>

<script>
    // Animate statistics counting
    document.addEventListener('DOMContentLoaded', function() {
        const statNumbers = document.querySelectorAll('.stat-number');
        const speed = 200;

        statNumbers.forEach(stat => {
            const target = parseInt(stat.getAttribute('data-count'));
            const count = parseInt(stat.innerText);
            const increment = target / speed;

            if (count < target) {
                stat.innerText = Math.floor(count + increment);
                setTimeout(updateCount, 1);
            } else {
                stat.innerText = target;
            }

            function updateCount() {
                const current = parseInt(stat.innerText);
                if (current < target) {
                    stat.innerText = Math.floor(current + increment);
                    setTimeout(updateCount, 1);
                } else {
                    stat.innerText = target + '+';
                }
            }
        });
    });



    $(document).ready(function () {
        $('.stat-number').each(function () {
            const $this = $(this);
            const countTo = parseInt($this.attr('data-count'), 10);

            $({ countNum: 0 }).animate({ countNum: countTo }, {
                duration: 1500,
                easing: 'swing',
                step: function () {
                    $this.text(Math.floor(this.countNum));
                },
                complete: function () {
                    $this.text(this.countNum);
                }
            });
        });
    });

</script>