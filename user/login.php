<?php
session_start();
include __DIR__ . '/../config/config.php';
include BASE_PATH . '/config/db.php';
include BASE_PATH . '/includes/header.php';

// Redirect if admin is logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

// Redirect if user or donor is logged in
if (!empty($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/user/dashboard.php');
    exit;
}

?>

<head>
  <title>Login</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/user/assets/css/signup.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .signup-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f2f2f2;
    }
  </style>
</head>

<div class="signup-wrapper">
  <div class="signup-container">
    <!-- Sliding showMessage -->
    <div id="showMessage" style="
      display: none;
      position: fixed;
      top: 70px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 9999;
      min-width: 300px;
      text-align: center;
    "></div>

    <h2>Login</h2>

    <form id="login-form" class="signup-form">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>

      <div class="checkbox-wrapper">
        <input type="checkbox" name="as_donor" id="as_donor_checkbox">
        <label for="as_donor_checkbox">Login as donor</label>
      </div>

      <button type="submit">Login</button>
    </form>

    <div class="login-link">
      Don't have an account? <a href="signup.php">Register</a>
    </div>
  </div>
</div>

<?php include BASE_PATH . "/admin/includes/footer_admin.php" ?>


<script>
  $(document).ready(function () {
    const showMessage = $('#showMessage');

    function showMsg(text, success = true) {
      showMessage
        .stop(true, true)
        .hide()
        .text(text)
        .css({
          'background-color': success ? '#e8f5e9' : '#ffebee',
          'color': success ? 'green' : 'red',
          'padding': '12px 25px',
          'border-radius': '6px',
          'font-weight': '500',
          'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.1)'
        })
        .slideDown();

      setTimeout(() => {
        showMessage.slideUp(() => {
          showMessage.text('').hide();
        });
      }, 3000);
    }

    <?php if (isset($_SESSION['logout_message'])): ?>
      showMsg("<?= $_SESSION['logout_message'] ?>", true);
      <?php unset($_SESSION['logout_message']); ?>
    <?php endif; ?>

    $('#login-form').on('submit', function (e) {
      e.preventDefault();

      $.ajax({
        url: '<?= BASE_URL ?>/user/php_files/login_handler.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (res) {
          showMsg(res.message, res.success);

          if (res.success) {
            setTimeout(() => {
              window.location.href = res.redirect || '<?= BASE_URL ?>/user/dashboard.php';
            }, 1000);
          }
        },
        error: function () {
          showMsg('An unexpected error occurred. Please try again.', false);
        }
      });
    });
  });
</script>


