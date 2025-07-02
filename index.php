<?php



session_start();
include __DIR__ . '/config/config.php';
require_once BASE_PATH . '/includes/header.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}
?>

<head>
  <link rel="stylesheet" href="<?= BASE_URL . '/assets/css/main_content.css' ?>">
</head>
<main class="content">
  <div class=" landing-page">
    <section class="hero">
      <h1>Donate Blood, Save Lives</h1>
      <p>One donation can save up to three lives. Be a hero today.</p>
      <a href="register.php" class="cta-button">Become a Donor</a>
    </section>

    <section class="info">
      <h2>Why Donate Blood?</h2>
      <p>Blood donation is a selfless act that helps patients in need of surgeries, cancer treatment, chronic illnesses, and traumatic injuries.</p>
    </section>

    <section class="how-it-works">
      <h2>How It Works</h2>
      <div class="steps">
        <div class="step">
          <h3>1. Register</h3>
          <p>Sign up as a blood donor and provide your basic details.</p>
        </div>
        <div class="step">
          <h3>2. Get Notified</h3>
          <p>We’ll notify you when your blood type is needed nearby.</p>
        </div>
        <div class="step">
          <h3>3. Donate</h3>
          <p>Visit the donation center or camp and give the gift of life.</p>
        </div>
      </div>
    </section>

    <section class="join-us">
      <h2>Join Our Mission</h2>
      <p>We connect donors with patients across the country. Your contribution matters.</p>
      <a href="about.php" class="cta-secondary">Learn More</a>
    </section>
  </div>
</main>


<?php
require_once BASE_PATH . '/includes/footer.php';
?>