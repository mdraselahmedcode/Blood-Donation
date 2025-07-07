<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}

// Redirect if user or donor is logged in
if (!empty($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/user/dashboard.php');
    exit;
}

$adminId = $_SESSION['admin_id'] ?? null;
$adminData = [];

if ($adminId) {
    $stmt = $conn->prepare("SELECT first_name, last_name, username, email, phone, gender, country, created_at FROM admins WHERE id = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $adminData = $result->fetch_assoc();
    $stmt->close();
}
?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/profile_admin.css">
    <style>
        select[name="gender"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: white;
            font-size: 15px;
        }

        /* select[name="gender"]:focus {
            border-color: #4CAF50;
            outline: none;
        } */

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-row>div {
            flex: 1;
        }

        /* Base input/select styles */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 15px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        /* Focus style for red outline */
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            outline: none;
            border: 1px solid #e53935;
            /* Theme red */
            box-shadow: 0 0 4px rgba(229, 57, 53, 0.4);
            /* Optional red glow */
        }
    </style>
</head>

<?php require_once BASE_PATH . '/admin/includes/header_admin.php'; ?>
<?php require_once BASE_PATH . '/admin/includes/sidebar_admin.php'; ?>

<main class="content" style="margin-top: -90px;">

    <!-- back button -->
    <div style="width:100%; text-align:left;">
        <h2 style="padding: 3px; text-align: left; margin: 0 0 20px; color: #C62828">Your Profile</h2>
        <a href="<?= BASE_URL ?>/admin/dashboard.php" style="display: inline-block; margin-bottom: 15px; padding: 8px 12px; background-color: #ccc; color: #000; text-decoration: none; border-radius: 4px;">
            Back
        </a>
    </div>
    <div id="showMessage" style="
        display: none;
        position: fixed;
        top: 90px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        min-width: 300px;
        text-align: center;
    "></div>

    <h2 style="text-align: center;">
        Admin Profile
        <?php if (!empty($_SESSION['super_admin']) && $_SESSION['super_admin'] == 1): ?>
            <span title="Super Admin" style="font-size:1.1em; margin-left:6px;">👑</span>
        <?php endif; ?>
    </h2>

    <?php if ($adminData): ?>
        <div class="profile-wrapper" style="display: flex; justify-content: center;">
            <form id="updateProfileForm" class="profile-form" style="max-width: 500px; width: 100%;">

                <div class="form-row">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($adminData['first_name']) ?>" required>
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($adminData['last_name']) ?>" required>
                    </div>
                </div>

                <div>
                    <label>Username (readonly)</label>
                    <input type="text" value="<?= htmlspecialchars($adminData['username']) ?>" readonly>
                </div>

                <div class="form-row">
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($adminData['email']) ?>" required>
                    </div>
                    <div>
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($adminData['phone']) ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="" disabled>Select Gender</option>
                            <option value="Male" <?= $adminData['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $adminData['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $adminData['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <label>Country</label>
                        <input type="text" name="country" value="<?= htmlspecialchars($adminData['country']) ?>" required>
                    </div>
                </div>

                <div>
                    <label>Member Since</label>
                    <input type="text" value="<?= htmlspecialchars($adminData['created_at']) ?>" readonly>
                </div>

                <button type="submit">Update Profile</button>
                <button type="button" id="openChangePasswordModal" style="margin-left: 10px;">Change Password</button>

            </form>
        </div>

        <!-- Change Password Modal -->
        <div id="modalOverlay" class="modal-overlay"></div>
        <div id="changePasswordModal" class="modal">
            <h3 style="text-align: center; margin-bottom: 20px;">Change Password</h3>
            <form id="changePasswordForm">
                <div class="modal-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="modal-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="modal-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <div class="modal-actions">
                    <button type="submit">Save</button>
                    <button type="button" id="closePasswordModal">Cancel</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</main>

<script src="<?= BASE_URL ?>/vendor/jquery/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#closePasswordModal, #modalOverlay').on('click', function() {
            $('#modalOverlay, #changePasswordModal').fadeOut();
            $('#changePasswordForm')[0].reset();
        });

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

        $('#updateProfileForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: '<?= BASE_URL ?>/admin/php_files/sections/profile/update.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    showMsg(res.message, res.success);
                },
                error: function() {
                    showMsg('Something went wrong. Please try again.', false);
                }
            });
        });

        $('#openChangePasswordModal').on('click', function() {
            $('#modalOverlay, #changePasswordModal').fadeIn();
        });

        $('#closePasswordModal, #modalOverlay').on('click', function() {
            $('#modalOverlay, #changePasswordModal').fadeOut();
            $('#changePasswordForm')[0].reset();
        });

        $('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: '<?= BASE_URL ?>/admin/php_files/sections/profile/change_password.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    showMsg(res.message, res.success);
                    if (res.success) {
                        $('#changePasswordModal').fadeOut();
                        $('#changePasswordForm')[0].reset();
                    }
                },
                error: function() {
                    showMsg('Something went wrong while changing password.', false);
                }
            });
        });
    });
</script>

<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>