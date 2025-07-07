<?php
session_start();
include __DIR__ . '/../config/config.php';
include BASE_PATH . '/config/db.php';
include BASE_PATH . '/includes/header.php';

// Redirect if already logged in (admin or any user/donor)
if (!empty($_SESSION['admin_logged_in']) || !empty($_SESSION['user_logged_in'])) {
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
  <title>Donor Signup</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/user/assets/css/signup.css">
</head>

<div class="" style="display: flex; justify-content: center; flex-direction: column; flex:1">
  <div class="signup-container">
    <div id="showMessage" style="display: none; position: fixed; top: 70px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px; text-align: center;"></div>
  
    <h2>Register as Donor</h2>
  
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
  
      <select name="city_id" required>
        <option value="">Select City</option>
        <?php foreach ($cities as $city): ?>
          <option value="<?= htmlspecialchars($city['id']) ?>"><?= htmlspecialchars($city['name']) ?></option>
        <?php endforeach; ?>
      </select>
  
      <input type="text" name="country" placeholder="Country" required>
      <input type="password" name="password" placeholder="Password" required>
  
      <!-- Donor-only field -->
      <select name="blood_group_id" required>
        <option value="">Select Blood Group</option>
        <?php foreach ($bloodGroups as $bg): ?>
          <option value="<?= htmlspecialchars($bg['id']) ?>"><?= htmlspecialchars($bg['name']) ?></option>
        <?php endforeach; ?>
      </select>
  
      <button type="submit">Sign Up</button>
    </form>
  
    <div class="login-link">
      Already have an account? <a href="login.php">Log in</a>
    </div>
  </div>
</div>

<?php include BASE_PATH . '/admin/includes/footer_admin.php' ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

  $('#signup-form').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
      url: '<?= BASE_URL ?>/user/php_files/signup_only_donor_handler.php',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (res) {
        showMsg(res.message, res.success);

        if (res.success) {
          $('#signup-form')[0].reset();
          setTimeout(() => {
            window.location.href = '<?= BASE_URL ?>/user/login.php';
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
