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


// Fetch blood groups
$bloodGroups = [];
$result = $conn->query("SELECT id, name FROM blood_groups ORDER BY name");
while ($row = $result->fetch_assoc()) {
    $bloodGroups[] = $row;
}
$result->free();

// Fetch cities
$cities = [];
$result = $conn->query("SELECT id, name FROM cities ORDER BY name");
while ($row = $result->fetch_assoc()) {
    $cities[] = $row;
}
$result->free();
?>

<head>
  <title>Signup</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/user/assets/css/signup.css">
</head>

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

  <h2>Register</h2>

  <div id="response-message"></div>

  <form id="signup-form" class="signup-form">
    <input type="text" name="name" placeholder="Full Name" required>

    <select name="gender" required>
      <option value="">Select Gender</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
      <option value="Other">Other</option>
    </select>

    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <textarea name="address" placeholder="Address" required></textarea>
    <input type="text" name="pin_code" placeholder="Pin Code" required>

    <!-- City Dropdown -->
    <select name="city_id" required>
      <option value="">Select City</option>
      <?php foreach ($cities as $city): ?>
        <option value="<?= htmlspecialchars($city['id']) ?>"><?= htmlspecialchars($city['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <input type="text" name="country" placeholder="Country" required>
    <input type="password" name="password" placeholder="Password" required>

    <div class="checkbox-wrapper">
      <input type="checkbox" name="is_donor" id="is_donor_checkbox">
      <label for="is_donor_checkbox">I want to register as a donor</label>
    </div>

    <div id="donor-fields" style="display:none;">
      <select name="blood_group_id" id="blood_group_id">
        <option value="">Select Blood Group</option>
        <?php foreach ($bloodGroups as $bg): ?>
          <option value="<?= htmlspecialchars($bg['id']) ?>"><?= htmlspecialchars($bg['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit">Sign Up</button>
  </form>

  <div class="login-link">
    Already have an account? <a href="login.php">Log in</a>
  </div>
</div>

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

    // Donor toggle
    $('#is_donor_checkbox').on('change', function () {
      if (this.checked) {
        $('#donor-fields').show();
        $('#blood_group_id').attr('required', true);
      } else {
        $('#donor-fields').hide();
        $('#blood_group_id').removeAttr('required').val('');
      }
    });

    // Form submission
    $('#signup-form').on('submit', function (e) {
      e.preventDefault();

      $.ajax({
        url: '<?= BASE_URL ?>/user/php_files/signup_user_handler.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (res) {
          showMsg(res.message, res.success);

          if (res.success) {
            $('#signup-form')[0].reset();
            $('#donor-fields').hide();
            $('#blood_group_id').removeAttr('required');
          }
        },
        error: function () {
          showMsg('An unexpected error occurred. Please try again.', false);
        }
      });
    });
  });
</script>
