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
    <!-- Sliding Message Box -->
    <div id="showMessage" style="display:none; position: fixed; top: 90px; left: 50%; transform: translateX(-50%);
        background-color: #e8f5e9; color: green; padding: 12px 25px; border-radius: 0 0 6px 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); z-index: 9999; font-weight: 500;">
    </div>
    <div class="login-page">
        <h2>Admin Login</h2>

        <!-- <div id="loginError" class="error-msg" style="display: none;"></div> -->

        <form id="adminLoginForm" class="login-form">
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
    <?php if (isset($_SESSION['logout_message'])): ?>
        
<!-- logout success message shown -->
<script>
    $(document).ready(function() {
        const messageBox = $('#showMessage');
        messageBox.text("<?= $_SESSION['logout_message'] ?>")
            .css({
                'background-color': '#e8f5e9',
                'color': 'green',
                'padding': '12px 25px',
                'border-radius': '0 0 6px 6px',
                'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.1)',
                'font-weight': '500',
                'position': 'fixed',
                'top': '90px',
                'left': '50%',
                'transform': 'translateX(-50%)',
                'z-index': '9999'
            }).slideDown();

        setTimeout(() => {
            messageBox.slideUp(() => {
                messageBox.text('').removeAttr('style').hide();
            });
        }, 3000);
    });
</script>
<?php unset($_SESSION['logout_message']); ?>
<?php endif; ?>

</main>

<script>
    $('#adminLoginForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const messageBox = $('#showMessage');

        $.ajax({
            url: '<?= BASE_URL . '/admin/php_files/login_admin_handler.php' ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    messageBox.text('Login successful! Redirecting...')
                        .css({
                            'background-color': '#e8f5e9',
                            'color': 'green',
                            'padding': '12px 25px',
                            'border-radius': '0 0 6px 6px',
                            'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.1)',
                            'font-weight': '500',
                            'position': 'fixed',
                            'top': '90px',
                            'left': '50%',
                            'transform': 'translateX(-50%)',
                            'z-index': '9999',
                            'display': 'none'
                        }).slideDown();

                    setTimeout(() => {
                        window.location.href = '<?= BASE_URL . '/admin/dashboard.php' ?>';
                    }, 1000);
                } else {
                    messageBox.text(res.errors.join(', '))
                        .css({
                            'background-color': '#ffebee',
                            'color': 'red',
                            'padding': '12px 25px',
                            'border-radius': '0 0 6px 6px',
                            'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.1)',
                            'font-weight': '500',
                            'position': 'fixed',
                            'top': '90px',
                            'left': '50%',
                            'transform': 'translateX(-50%)',
                            'z-index': '9999',
                            'display': 'none'
                        }).slideDown();

                    setTimeout(() => {
                        messageBox.slideUp(() => {
                            messageBox.text('');
                            // Restore fixed positioning styles after hiding
                            messageBox.css({
                                'background-color': '#e8f5e9',
                                'color': 'green',
                                'padding': '12px 25px',
                                'border-radius': '0 0 6px 6px',
                                'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.1)',
                                'font-weight': '500',
                                'position': 'fixed',
                                'top': '90px',
                                'left': '50%',
                                'transform': 'translateX(-50%)',
                                'z-index': '9999',
                                'display': 'none'
                            });
                        });
                    }, 3000);
                }
            },
            error: function() {
                messageBox.text('An unexpected error occurred. Please try again.')
                    .css({
                        'background-color': '#ffebee',
                        'color': 'red',
                        'padding': '12px 25px',
                        'border-radius': '0 0 6px 6px',
                        'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.1)',
                        'font-weight': '500',
                        'position': 'fixed',
                        'top': '90px',
                        'left': '50%',
                        'transform': 'translateX(-50%)',
                        'z-index': '9999',
                        'display': 'none'
                    }).slideDown();

                setTimeout(() => {
                    messageBox.slideUp(() => {
                        messageBox.text('');
                        // Restore fixed positioning styles after hiding
                        messageBox.css({
                            'background-color': '#e8f5e9',
                            'color': 'green',
                            'padding': '12px 25px',
                            'border-radius': '0 0 6px 6px',
                            'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.1)',
                            'font-weight': '500',
                            'position': 'fixed',
                            'top': '90px',
                            'left': '50%',
                            'transform': 'translateX(-50%)',
                            'z-index': '9999',
                            'display': 'none'
                        });
                    });
                }, 3000);
            }
        });
    });
</script>

<?php
require_once BASE_PATH . '/admin/includes/footer_admin.php';
?>
