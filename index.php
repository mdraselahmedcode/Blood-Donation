<?php
session_start();
include __DIR__ . '/config/config.php';
require_once BASE_PATH . '/config/db.php';
require_once BASE_PATH . '/includes/header.php';



// Admin redirect
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
  header('Location: ' . BASE_URL . '/admin/dashboard.php');
  exit;
}

// User (Donor or Normal) redirect
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
  $redirectPath = (!empty($_SESSION['is_donor']) && $_SESSION['is_donor'] == 1)
    ? '/user/dashboard.php'
    : '/user/dashboard.php'; // same path now, can be customized

  header('Location: ' . BASE_URL . $redirectPath);
  exit;
}


$activeDonorsCount = 0;

$query = $conn->query("SELECT COUNT(*) as total FROM donors WHERE status = 'Active'");
if ($query && $row = $query->fetch_assoc()) {
  $activeDonorsCount = $row['total'];
}
?>



<head>
  <!-- <link rel="stylesheet" href="<?= BASE_URL . '/assets/css/main_content.css' ?>"> -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    /* Improved CSS */
    :root {
      --primary: #e53935;
      --primary-dark: #b71c1c;
      --primary-light: #ffcdd2;
      --secondary: #424242;
      --light: #f5f5f5;
      --white: #ffffff;
      --text: #333333;
    }

    .landing-page {
      font-family: 'Poppins', sans-serif;
      color: var(--text);
      overflow-x: hidden;
    }

    /* Hero Section */
    .hero {
      display: flex;
      align-items: center;
      min-height: 80vh;
      padding: 60px 5%;
      background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
      color: var(--white);
    }

    .hero-content {
      flex: 1;
      max-width: 600px;
    }

    .hero h1 {
      font-size: 3.5rem;
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 20px;
    }

    .highlight {
      color: var(--primary-light);
    }

    .hero-text {
      font-size: 1.3rem;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    /* .hero-image {
      flex: 1;
      display: flex;
      justify-content: center;
    }

    .hero-image img {
      max-width: 65%;
      height: auto;
      animation: float 6s ease-in-out infinite;
    } */

    @keyframes float {
      0% {
        transform: translateY(0px);
      }

      50% {
        transform: translateY(-20px);
      }

      100% {
        transform: translateY(0px);
      }
    }

    /* CTA Buttons */
    .cta-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      margin-top: 30px;
    }

    .cta-button {
      background-color: var(--white);
      color: var(--primary-dark);
      padding: 15px 30px;
      font-weight: 600;
      text-decoration: none;
      border-radius: 50px;
      display: inline-block;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .cta-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      background-color: var(--primary-light);
    }

    .cta-secondary {
      color: var(--white);
      background-color: transparent;
      border: 2px solid var(--white);
      padding: 15px 30px;
      font-weight: 600;
      text-decoration: none;
      border-radius: 50px;
      display: inline-block;
      transition: all 0.3s ease;
    }

    .cta-secondary:hover {
      background-color: rgba(255, 255, 255, 0.1);
      transform: translateY(-3px);
    }

    /* Section Styling */
    .section-header {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-header h2 {
      font-size: 2.5rem;
      color: var(--primary-dark);
      margin-bottom: 15px;
    }

    .section-subtitle {
      font-size: 1.2rem;
      color: var(--secondary);
      max-width: 700px;
      margin: 0 auto;
    }

    /* Info Section */
    .info {
      padding: 100px 5%;
      background-color: var(--white);
    }

    .benefits-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 40px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .benefit-card {
      background: var(--light);
      border-radius: 15px;
      padding: 40px 30px;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .benefit-card:hover {
      transform: translateY(-10px);
    }

    .benefit-icon {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 20px;
    }

    .benefit-card h3 {
      font-size: 1.5rem;
      margin-bottom: 15px;
    }

    .benefit-card p {
      color: var(--secondary);
      line-height: 1.6;
    }

    /* How It Works Section */
    .how-it-works {
      padding: 100px 5%;
      background-color: var(--light);
    }

    .steps {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .step {
      background: var(--white);
      border-radius: 15px;
      padding: 40px 30px;
      text-align: center;
      position: relative;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .step-number {
      width: 60px;
      height: 60px;
      background: var(--primary);
      color: var(--white);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      font-weight: 700;
      margin: 0 auto 25px;
    }

    .step h3 {
      font-size: 1.5rem;
      margin-bottom: 15px;
      color: var(--primary-dark);
    }

    .step p {
      color: var(--secondary);
      line-height: 1.6;
    }

    /* Stats Section */
    .stats {
      display: flex;
      justify-content: space-around;
      padding: 80px 5%;
      background: var(--primary-dark);
      color: var(--white);
      text-align: center;
    }

    .stat-item {
      padding: 0 20px;
    }

    .stat-number {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .stat-label {
      font-size: 1.2rem;
      opacity: 0.9;
    }

    /* Join Us Section */
    .join-us {
      padding: 100px 5%;
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('<?= BASE_URL ?>/assets/images/donation-bg.jpg');
      background-size: cover;
      background-position: center;
      color: var(--white);
      text-align: center;
    }

    .join-content {
      max-width: 800px;
      margin: 0 auto;
    }

    .join-us h2 {
      font-size: 2.5rem;
      margin-bottom: 20px;
    }

    .join-us p {
      font-size: 1.2rem;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
      .hero {
        flex-direction: column;
        text-align: center;
        padding-top: 100px;
      }

      .hero-content {
        margin-bottom: 50px;
      }

      .hero h1 {
        font-size: 2.8rem;
      }

      .cta-container {
        justify-content: center;
      }
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.2rem;
      }

      .section-header h2 {
        font-size: 2rem;
      }

      .stats {
        flex-direction: column;
        gap: 40px;
      }
    }

    @media (max-width: 576px) {
      .cta-container {
        flex-direction: column;
        gap: 15px;
      }

      .cta-button,
      .cta-secondary {
        width: 100%;
        text-align: center;
      }
    }

    .hero {
      display: flex;
      align-items: center;
      min-height: 90vh;
      padding: 60px 5%;
      background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
      color: var(--white);
      position: relative;
      overflow: hidden;
    }

    /* Add decorative blood drop shapes in background */
    .hero::before {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 300px;
      height: 300px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
      z-index: 0;
    }

    .hero::after {
      content: '';
      position: absolute;
      bottom: -100px;
      left: -100px;
      width: 400px;
      height: 400px;
      background: rgba(255, 255, 255, 0.03);
      border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
      z-index: 0;
    }

    .hero-content {
      flex: 1;
      max-width: 600px;
      position: relative;
      z-index: 1;
    }

    .hero h1 {
      font-size: 3.5rem;
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 20px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .highlight {
      color: var(--primary-light);
      position: relative;
      display: inline-block;
    }

    /* Animated underline for highlight text */
    .highlight::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 100%;
      height: 3px;
      background: var(--primary-light);
      transform: scaleX(0);
      transform-origin: right;
      transition: transform 0.5s ease;
    }

    .hero:hover .highlight::after {
      transform: scaleX(1);
      transform-origin: left;
    }

    .hero-image {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      z-index: 1;
    }

    .hero-image img {
      max-width: 65%;
      height: auto;
      filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.2));
      animation: float 6s ease-in-out infinite;
    }

    /* Enhanced floating animation */
    @keyframes float {
      0% {
        transform: translateY(0px) rotate(0deg);
      }

      50% {
        transform: translateY(-20px) rotate(2deg);
      }

      100% {
        transform: translateY(0px) rotate(0deg);
      }
    }

    /* Add pulse effect to CTA button */
    .cta-button {
      position: relative;
      overflow: hidden;
    }

    .cta-button::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: rgba(255, 255, 255, 0.1);
      transform: scale(0);
      border-radius: 50%;
      opacity: 0;
      transition: transform 0.5s, opacity 1s;
    }

    .cta-button:hover::after {
      transform: scale(1);
      opacity: 1;
    }

    /* Add decorative elements */
    .hero-decoration {
      position: absolute;
      width: 100px;
      height: 100px;
      background: url('<?= BASE_URL ?>/assets/images/blood-drop.svg') no-repeat;
      background-size: contain;
      opacity: 0.1;
      z-index: 0;
    }

    .decoration-1 {
      top: 20%;
      left: 5%;
      animation: float 8s ease-in-out infinite 1s;
    }

    .decoration-2 {
      bottom: 15%;
      right: 10%;
      animation: float 7s ease-in-out infinite 2s;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
      .hero {
        min-height: auto;
        padding: 100px 5% 80px;
      }

      .hero-image img {
        max-width: 80%;
        margin-top: 40px;
      }

      .hero::before,
      .hero::after {
        display: none;
      }
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.8rem;
      }

      .hero-image img {
        max-width: 100%;
      }
    }

    /* How It Works Section Styling */
    .how-it-works {
      padding: 80px 5%;
      background-color: var(--light);
    }

    .user-flow-tabs {
      max-width: 1000px;
      margin: 0 auto;
      background: var(--white);
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }

    .tab-buttons {
      display: flex;
      border-bottom: 2px solid var(--primary-light);
    }

    .tab-button {
      flex: 1;
      padding: 18px 20px;
      background: transparent;
      border: none;
      font-family: 'Poppins', sans-serif;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--secondary);
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
    }

    .tab-button.active {
      color: var(--primary-dark);
    }

    .tab-button.active::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 100%;
      height: 3px;
      background: var(--primary);
    }

    .tab-button:hover:not(.active) {
      background: rgba(229, 57, 53, 0.05);
    }

    .tab-content {
      padding: 30px;
    }

    .flow-steps {
      display: none;
      animation: fadeIn 0.5s ease-out;
    }

    .flow-steps.active {
      display: block;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .step {
      display: flex;
      align-items: center;
      padding: 25px 0;
      position: relative;
    }

    .step:not(:last-child)::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 30px;
      right: 30px;
      height: 1px;
      background: rgba(0, 0, 0, 0.1);
    }

    .step-number {
      width: 40px;
      height: 40px;
      background: var(--primary);
      color: var(--white);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      margin-right: 20px;
      flex-shrink: 0;
    }

    .step-content {
      flex: 1;
    }

    .step-content h3 {
      font-size: 1.3rem;
      color: var(--primary-dark);
      margin-bottom: 8px;
    }

    .step-content p {
      color: var(--secondary);
      line-height: 1.6;
    }

    .step-icon {
      width: 50px;
      height: 50px;
      background: var(--primary-light);
      color: var(--primary);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      margin-left: 20px;
      flex-shrink: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .tab-buttons {
        flex-direction: column;
      }

      .step {
        flex-direction: column;
        text-align: center;
        padding: 30px 0;
      }

      .step-number {
        margin-right: 0;
        margin-bottom: 15px;
      }

      .step-content {
        margin-bottom: 15px;
      }

      .step-icon {
        margin-left: 0;
        margin-top: 15px;
      }
    }
  </style>

</head>

<main class="content">
  <div class="landing-page">
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>Donate Blood, <span class="highlight">Save Lives</span></h1>
        <p class="hero-text">One donation can save up to three lives. Join our community of heroes today.</p>
        <div class="cta-container">
          <a href="<?= BASE_URL ?>/user/signup.php" class="cta-button">Become a Donor</a>
          <a href="<?= BASE_URL ?>/user/login.php" class="cta-secondary">Already a Donor?</a>
        </div>
      </div>
      <div class="hero-image">
        <img src="<?= BASE_URL ?>/assets/images/hero-donation.jpg" alt="Blood donation illustration">
      </div>
    </section>

    <!-- Info Section -->
    <section class="info">
      <div class="section-header">
        <h2>Why Donate Blood?</h2>
        <p class="section-subtitle">Your donation makes a life-saving difference</p>
      </div>
      <div class="benefits-grid">
        <div class="benefit-card">
          <div class="benefit-icon">
            <i class="fas fa-heartbeat"></i>
          </div>
          <h3>Saves Lives</h3>
          <p>Your blood can help patients in surgeries, cancer treatments, and emergency situations.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">
            <i class="fas fa-users"></i>
          </div>
          <h3>Strengthens Community</h3>
          <p>Join thousands of donors helping neighbors in need across our region.</p>
        </div>
        <div class="benefit-card">
          <div class="benefit-icon">
            <i class="fas fa-heart"></i>
          </div>
          <h3>Health Benefits</h3>
          <p>Donating blood helps reduce iron overload and stimulates new blood cell production.</p>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works">
      <div class="section-header">
        <h2>How Our System Works</h2>
        <p class="section-subtitle">Connecting blood donors with those in need</p>
      </div>

      <div class="user-flow-tabs">
        <div class="tab-buttons">
          <button class="tab-button active" data-tab="donor-flow">I'm a Donor</button>
          <button class="tab-button" data-tab="receiver-flow">I Need Blood</button>
        </div>

        <div class="tab-content">
          <!-- Donor Flow -->
          <div id="donor-flow" class="flow-steps active">
            <div class="step">
              <div class="step-number">1</div>
              <div class="step-content">
                <h3>Register as Donor</h3>
                <p>Sign up with your details, blood type, and location to join our donor network.</p>
              </div>
              <div class="step-icon">
                <i class="fas fa-user-plus"></i>
              </div>
            </div>

            <div class="step">
              <div class="step-number">2</div>
              <div class="step-content">
                <h3>Get Donation Requests</h3>
                <p>Receive notifications when patients in your city need your blood type.</p>
              </div>
              <div class="step-icon">
                <i class="fas fa-bell"></i>
              </div>
            </div>

            <div class="step">
              <div class="step-number">3</div>
              <div class="step-content">
                <h3>Respond & Donate</h3>
                <p>Confirm availability and donate at a nearby blood bank or hospital.</p>
              </div>
              <div class="step-icon">
                <i class="fas fa-hand-holding-heart"></i>
              </div>
            </div>
          </div>

          <!-- Receiver Flow -->
          <div id="receiver-flow" class="flow-steps">
            <div class="step">
              <div class="step-number">1</div>
              <div class="step-content">
                <h3>Register Your Need</h3>
                <p>Create an account and verify your blood requirement details.</p>
              </div>
              <div class="step-icon">
                <i class="fas fa-clipboard-list"></i>
              </div>
            </div>

            <div class="step">
              <div class="step-number">2</div>
              <div class="step-content">
                <h3>Search Donors</h3>
                <p>Find compatible donors in your city using our search system.</p>
              </div>
              <div class="step-icon">
                <i class="fas fa-search-location"></i>
              </div>
            </div>

            <div class="step">
              <div class="step-number">3</div>
              <div class="step-content">
                <h3>Contact & Arrange</h3>
                <p>View donor contact details and coordinate the blood donation.</p>
              </div>
              <div class="step-icon">
                <i class="fas fa-phone-alt"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
      <div class="stat-item">
        <div class="stat-number" data-count="10000">130</div>
        <div class="stat-label">Lives Saved</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-count="<?= $activeDonorsCount ?>">
          <?= $activeDonorsCount ?>
        </div>
        <div class="stat-label">Active Donors</div>
      </div>
      <div class="stat-item">
        <div class="stat-number" data-count="150">3</div>
        <div class="stat-label">Donation Centers</div>
      </div>
    </section>

    <!-- Join Us Section -->
    <section class="join-us">
      <div class="join-content">
        <h2>Ready to Make a Difference?</h2>
        <p>Join our mission to ensure no patient goes without life-saving blood when they need it most.</p>
        <div class="cta-container">
          <a href="<?= BASE_URL ?>/user/signup.php" class="cta-button">Join Now</a>
          <a href="<?= BASE_URL ?>/about.php" class="cta-secondary">Learn More</a>
        </div>
      </div>
    </section>
  </div>
</main>

<script>
  $(document).ready(function() {
    $('.tab-button').on('click', function() {
      // Remove 'active' from all buttons and tab contents
      $('.tab-button').removeClass('active');
      $('.flow-steps').removeClass('active');

      // Add 'active' to clicked button
      $(this).addClass('active');

      // Get tab ID and activate it
      const tabId = $(this).data('tab');
      $('#' + tabId).addClass('active');
    });
  });
</script>


<?php
require_once BASE_PATH . '/includes/footer.php';
?>