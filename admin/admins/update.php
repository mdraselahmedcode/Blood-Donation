<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] !== true ||
    empty($_SESSION['super_admin']) ||
    $_SESSION['super_admin'] != 1
) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}

// Redirect if user or donor is logged in
if (!empty($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/user/dashboard.php');
    exit;
}

// Get admin ID from query string
$adminId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($adminId === 0) {
    echo "Invalid admin ID.";
    exit;
}

// Fetch admin info
$stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

if (!$admin) {
    echo "Admin not found.";
    exit;
}


// Fetch the logged-in admin's protection status
$currentAdminId = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT is_protected FROM admins WHERE id = ?");
$stmt->bind_param("i", $currentAdminId);
$stmt->execute();
$result = $stmt->get_result();
$currentAdmin = $result->fetch_assoc();
$stmt->close();

$currentIsProtected = $currentAdmin['is_protected'] ?? 0;


// Gender options for select
$genders = ['Male', 'Female', 'Other'];

require_once BASE_PATH . '/admin/includes/header_admin.php';
require_once BASE_PATH . '/admin/includes/sidebar_admin.php';
?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/add_donor_admin.css' ?>">
</head>

<main class="content">
    <div id="showMessage" style="display:none; position: fixed; top: 70px; left: 56%; transform: translateX(-56%);
        background-color: #e8f5e9; color: green; padding: 12px 25px; border-radius: 0 0 6px 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1); z-index: 9999; font-weight: 500;">
    </div>

    <h2>Edit Admin</h2>
    <a href="<?= BASE_URL ?>/admin/admins/index.php" style="display:inline-block; margin-bottom: 15px;">← Back to Admins List</a>

    <form id="editAdminForm" style="max-width:600px;">
        <input type="hidden" name="id" value="<?= $admin['id'] ?>">

        <div style="margin-bottom: 15px;">
            <input type="text" name="first_name" placeholder="First Name" required value="<?= htmlspecialchars($admin['first_name']) ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="last_name" placeholder="Last Name" required value="<?= htmlspecialchars($admin['last_name']) ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($admin['username']) ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <input type="email" name="email" placeholder="Email Address" required value="<?= htmlspecialchars($admin['email']) ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="phone" placeholder="Phone Number" required value="<?= htmlspecialchars($admin['phone']) ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <select name="gender" required>
                <option value="" disabled>Select Gender</option>
                <?php foreach ($genders as $gender): ?>
                    <option value="<?= $gender ?>" <?= $admin['gender'] === $gender ? 'selected' : '' ?>><?= $gender ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="country" placeholder="Country" required value="<?= htmlspecialchars($admin['country']) ?>">
        </div>

        <div style="margin-bottom: 15px;">
            <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
        </div>

        <div style="margin-bottom: 15px;">
            <input type="password" name="confirm_password" placeholder="Confirm New Password">
        </div>

        <?php if ($_SESSION['super_admin'] == 1 && $currentIsProtected == 1): ?>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 14px;">
                    <input
                        type="checkbox"
                        name="super_admin"
                        value="1"
                        <?= $admin['super_admin'] ? 'checked' : '' ?>
                        <?= $admin['is_protected'] ? 'disabled' : '' ?>>
                    Make this admin a Super Admin
                    <?php if ($admin['is_protected']): ?>
                        <span style="color: #888; font-size: 12px;">(Protected: Cannot change this Super Admin status)</span>
                    <?php endif; ?>
                </label>
            </div>
        <?php endif; ?>


        <button type="submit">Update Admin</button>
    </form>
</main>

<script src="<?= BASE_URL ?>/vendor/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        const form = $('#editAdminForm');
        const showMessage = $('#showMessage');

        form.on('submit', function(e) {
            e.preventDefault();

            const submitBtn = form.find('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: '<?= BASE_URL ?>/admin/php_files/sections/admins/update.php',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(res) {
                    showMessage
                        .stop(true, true)
                        .hide()
                        .text(res.message)
                        .css({
                            'background-color': res.success ? '#e8f5e9' : '#ffebee',
                            'color': res.success ? 'green' : 'red'
                        })
                        .slideDown();

                    setTimeout(() => showMessage.slideUp(), 3000);

                    if (res.success) {
                        setTimeout(() => {
                            window.location.href = "<?= BASE_URL ?>/admin/admins/index.php";
                        }, 1500);
                    }
                },
                error: function() {
                    showMessage
                        .stop(true, true)
                        .hide()
                        .text('Something went wrong.')
                        .css({
                            'background-color': '#ffebee',
                            'color': 'red'
                        })
                        .slideDown();

                    setTimeout(() => showMessage.slideUp(), 3000);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text('Update Admin');
                }
            });
        });
    });
</script>

<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>