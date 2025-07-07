<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (
    !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true ||
    empty($_SESSION['super_admin']) || $_SESSION['super_admin'] != 1
) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}

// Redirect if user or donor is logged in
if (!empty($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/user/dashboard.php');
    exit;
}

require_once BASE_PATH . '/admin/includes/header_admin.php';
require_once BASE_PATH . '/admin/includes/sidebar_admin.php';
?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/add_donor_admin.css' ?>">
</head>

<main class="content">
    <div id="showMessage" style="display:none; position: fixed; top: 70px; left: 56%; transform: translateX(-56%);
        background-color: #e8f5e9; color: green; padding: 12px 25px; border-radius: 0 0 6px 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); z-index: 9999; font-weight: 500;">
    </div>

    <a href="<?= BASE_URL ?>/admin/admins/index.php" style="display: inline-block; margin-bottom: 15px; padding: 8px 12px; background-color: #ccc; color: #000; text-decoration: none; border-radius: 4px;">
        ← Back to Admins List
    </a>
    <h2 style="text-align: center;">Add New Admin</h2>

    <form id="addAdmin" style="max-width: 600px;">
        <div style="margin-bottom: 15px;">
            <input type="text" name="first_name" placeholder="First Name" required>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="last_name" placeholder="Last Name" required>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="email" name="email" placeholder="Email Address" required>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="phone" placeholder="Phone Number" required>
        </div>

        <!-- Gender Field -->
        <div style="margin-bottom: 15px;">
            <select name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <!-- Country Field -->
        <div style="margin-bottom: 15px;">
            <input type="text" name="country" placeholder="Country" required>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-size: 14px;">
                <input type="checkbox" name="super_admin" value="1">
                Make this admin a Super Admin
            </label>
        </div>

        <button type="submit">Add Admin</button>
    </form>
</main>

<script src="<?= BASE_URL ?>/vendor/jquery/jquery.min.js"></script>
<script>
$(document).ready(function () {
    const form = $('#addAdmin');
    const showMessage = $('#showMessage');
    const submitBtn = form.find('button[type="submit"]');

    form.on('submit', function (e) {
        e.preventDefault();
        submitBtn.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: '<?= BASE_URL ?>/admin/php_files/sections/admins/add.php',
            type: 'POST',
            data: form.serialize(), // All form fields including gender & country are serialized here
            dataType: 'json',
            success: function (res) {
                showMessage
                    .stop(true, true)
                    .hide()
                    .text(res.message)
                    .css({
                        'background-color': res.success ? '#e8f5e9' : '#ffebee',
                        'color': res.success ? 'green' : 'red'
                    })
                    .slideDown();

                setTimeout(() => {
                    showMessage.slideUp();
                }, 3000);

                if (res.success) {
                    form[0].reset();
                    setTimeout(() => {
                        window.location.href = "<?= BASE_URL ?>/admin/admins/index.php";
                    }, 1500);
                }
            },
            error: function () {
                showMessage
                    .stop(true, true)
                    .hide()
                    .text('Something went wrong.')
                    .css({
                        'background-color': '#ffebee',
                        'color': 'red'
                    })
                    .slideDown();

                setTimeout(() => {
                    showMessage.slideUp();
                }, 3000);
            },
            complete: function () {
                submitBtn.prop('disabled', false).text('Add Admin');
            }
        });
    });
});
</script>

<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>
