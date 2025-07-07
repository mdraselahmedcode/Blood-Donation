<?php
session_start();
include __DIR__ . '/config/config.php';
require_once BASE_PATH . '/includes/header.php';
?>

<head>
  <link rel="stylesheet" href="<?= BASE_URL . '/assets/css/contact.css' ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<main class="contact-page">
  <section class="contact-hero">
    <div class="container">
      <h1>Contact BloodCare</h1>
      <p>Have questions or feedback? We'd love to hear from you.</p>
    </div>
  </section>

  <section class="contact-content">
    <div class="container">
      <div class="contact-grid">
        <!-- Contact Form -->
        <div class="contact-form-container">
          <h2>Send Us a Message</h2>
          <form id="contactForm" action="<?= BASE_URL ?>/includes/send_contact_email.php" method="POST">
            <div class="form-group">
              <label for="name">Your Name</label>
              <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
              <label for="subject">Subject</label>
              <select id="subject" name="subject" required>
                <option value="" disabled selected>Select a subject</option>
                <option value="General Inquiry">General Inquiry</option>
                <option value="Donation Question">Donation Question</option>
                <option value="Partnership">Partnership</option>
                <option value="Technical Support">Technical Support</option>
                <option value="Feedback">Feedback</option>
                <option value="Other">Other</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="message">Your Message</label>
              <textarea id="message" name="message" rows="6" required></textarea>
            </div>
            
            <button type="submit" class="submit-btn">
              <i class="fas fa-paper-plane"></i> Send Message
            </button>
          </form>
        </div>

        <!-- Contact Info -->
        <div class="contact-info">
          <h2>Other Ways to Reach Us</h2>
          
          <div class="info-card">
            <div class="info-icon">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="info-content">
              <h3>Email Us</h3>
              <a href="mailto:mdraselahmed.code@gmail.com">mdraselahmed.code@gmail.com</a>
              <p>Typically responds within 24 hours</p>
            </div>
          </div>
          
          <div class="info-card">
            <div class="info-icon">
              <i class="fas fa-phone-alt"></i>
            </div>
            <div class="info-content">
              <h3>Call Us</h3>
              <a href="tel:+8801929951023">+880 1929 951023</a>
              <p>Monday-Friday, 9am-5pm</p>
            </div>
          </div>
          
          <div class="info-card">
            <div class="info-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="info-content">
              <h3>Visit Us</h3>
              <p>123 BloodCare Avenue<br>Dhaka 1212, Bangladesh</p>
            </div>
          </div>
          
          <div class="social-links">
            <h3>Follow Us</h3>
            <div class="social-icons">
              <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
              <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
              <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
              <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Map Section -->
  <section class="map-section">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.793841925877!2d90.4066373153856!3d23.7509690845878!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8b33cffc3fb%3A0x4a826f475fd312af!2sDhaka!5e0!3m2!1sen!2sbd!4v1620000000000!5m2!1sen!2sbd" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
    </iframe>
  </section>
</main>

<?php
require_once BASE_PATH . '/includes/footer.php';
?>