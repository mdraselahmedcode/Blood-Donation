<?php
session_start();
include __DIR__ . '/../config/config.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}
?>

<head>
    <!-- Login Page Styles -->
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/loginPage_admin.css' ?>">
</head>

<?php
require_once BASE_PATH . '/admin/includes/header_admin.php';
?>

<main class="content login-content">
    <div class="login-page">
        <h2>Admin Login</h2>

        <div id="loginError" class="error-msg" style="display: none;"></div>

        <form id="adminLoginForm" class="login-form">
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</main>


<script>
    $('#adminLoginForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.ajax({
            url: '<?= BASE_URL . '/admin/php_files/login_admin_handler.php' ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    window.location.href = '<?= BASE_URL . '/admin/dashboard.php' ?>';
                } else {
                    $('#loginError').text(res.errors.join(', ')).show();
                }
            },
            error: function(xhr, status, error) {
                $('#loginError').text('An unexpected error occurred. Please try again.').show();
            }
        });
    });
</script>

<?php
require_once BASE_PATH . '/admin/includes/footer_admin.php';
?>