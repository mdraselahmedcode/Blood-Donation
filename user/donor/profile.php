<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/user/login.php');
    exit;
}

// Only allow if donor
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'donor') {
    echo "<main class='content'><h2 style='text-align:center;color:red;'>You are not registered as a donor. Please use the user profile page.</h2></main>";
    require_once BASE_PATH . '/includes/footer.php';
    exit;
}

$userId = $_SESSION['user_id'] ?? null;
$userData = [];
$cities = [];
$bloodGroups = [];

// Fetch donor data
if ($userId) {
    $stmt = $conn->prepare("SELECT name, email, gender, phone, address, pin_code, city_id, country, blood_group_id, status, created_at FROM donors WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();
}

// Fetch cities for dropdown
$cityResult = $conn->query("SELECT id, name FROM cities ORDER BY name ASC");
while ($row = $cityResult->fetch_assoc()) {
    $cities[] = $row;
}

// Fetch blood groups for dropdown
$groupResult = $conn->query("SELECT id, name FROM blood_groups ORDER BY name ASC");
while ($row = $groupResult->fetch_assoc()) {
    $bloodGroups[] = $row;
}
?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>/user/assets/css/profile.css">
</head>

<?php require_once BASE_PATH . '/includes/header.php'; ?>
<?php require_once BASE_PATH . '/includes/sidebar.php'; ?>

<main class="content">
    <div style="width:100%; text-align:left;">
        <a href="<?= BASE_URL ?>/user/dashboard.php" style="display: inline-block; margin-bottom: 15px; padding: 8px 12px; background-color: #ccc; color: #000; text-decoration: none; border-radius: 4px;">
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

    <h2 style="text-align: center;">My Donor Profile</h2>

    <?php if ($userData): ?>
        <div class="profile-wrapper" style="display: flex; justify-content: center;">
            <form id="updateProfileForm" class="profile-form" style="max-width: 500px; width: 100%;">
                <div class="form-row">
                    <div>
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($userData['name']) ?>" required>
                    </div>
                    <div>
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?= $userData['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $userData['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $userData['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>
                    </div>
                    <div>
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($userData['phone']) ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label>Blood Group</label>
                        <select name="blood_group_id" required>
                            <option value="">Select Blood Group</option>
                            <?php foreach ($bloodGroups as $group): ?>
                                <option value="<?= $group['id'] ?>" <?= $userData['blood_group_id'] == $group['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($group['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>Status</label>
                        <select name="status" required>
                            <option value="Active" <?= $userData['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive" <?= $userData['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label>City</label>
                        <select name="city_id" required>
                            <option value="">Select City</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?= $city['id'] ?>" <?= $userData['city_id'] == $city['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($city['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>Country</label>
                        <input type="text" name="country" value="<?= htmlspecialchars($userData['country']) ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label>PIN Code</label>
                        <input type="text" name="pin_code" value="<?= htmlspecialchars($userData['pin_code']) ?>" required>
                    </div>
                    <div>
                        <label>Address</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($userData['address']) ?>" required>
                    </div>
                </div>
                <div>
                    <label>Member Since</label>
                    <input type="text" value="<?= htmlspecialchars($userData['created_at']) ?>" readonly>
                </div>
                <button type="submit">Update Profile</button>
                <button type="button" id="openChangePasswordModal" style="margin-left: 10px;">Change Password</button>
            </form>
        </div>

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
                url: '<?= BASE_URL ?>/user/php_files/donor/profile/update.php',
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
                url: '<?= BASE_URL ?>/user/php_files/donor/profile/change_password.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    showMsg(res.message, res.success);
                    if (res.success) {
                        $('#changePasswordModal, #modalOverlay').fadeOut();
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

<?php require_once BASE_PATH . '/includes/footer.php'; ?>