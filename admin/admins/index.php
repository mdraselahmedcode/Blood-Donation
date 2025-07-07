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

// Fetch current admin's protection status
$currentAdminId = $_SESSION['admin_id'];
$stmt = $conn->prepare("SELECT is_protected FROM admins WHERE id = ?");
$stmt->bind_param("i", $currentAdminId);
$stmt->execute();
$result = $stmt->get_result();
$currentAdmin = $result->fetch_assoc();
$stmt->close();

$currentIsProtected = $currentAdmin['is_protected'] ?? 0;

// Fetch all admins
$admins = [];
$res = $conn->query("SELECT id, first_name, last_name, username, gender, email, phone, super_admin, country, created_at FROM admins ORDER BY created_at DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $admins[] = $row;
    }
}

// Session message for redirects
$message = '';
if (isset($_SESSION['admin_message'])) {
    $message = $_SESSION['admin_message'];
    unset($_SESSION['admin_message']);
}

require_once BASE_PATH . '/admin/includes/header_admin.php';
require_once BASE_PATH . '/admin/includes/sidebar_admin.php';
?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/donors_admin.css">
</head>

<!-- Sliding Message Box -->
<div id="showMessage" style="display:none; position: fixed; top: 70px; left: 56%; transform: translateX(-56%);
    background-color: #e8f5e9; color: green; padding: 12px 25px; border-radius: 0 0 6px 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); z-index: 9999; font-weight: 500;">
</div>

<main class="content">
    <h2>Admin Management</h2>

    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="back-button">← Back</a>
    <a href="<?= BASE_URL ?>/admin/admins/add_admin.php" class="add-donor-btn">+ Add New Admin</a>

    <div class="scroll-table">
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Super Admin</th>
                    <th>Country</th>
                    <th>Created At</th>
                    <?php if ($_SESSION['super_admin'] == 1 && $currentIsProtected == 1): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($admins)): ?>
                    <tr>
                        <td colspan="<?= ($_SESSION['super_admin'] == 1 && $currentIsProtected == 1) ? 10 : 9 ?>" style="text-align:center;">No admins found.</td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1; foreach ($admins as $admin): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) ?></td>
                            <td><?= htmlspecialchars($admin['username']) ?></td>
                            <td><?= htmlspecialchars($admin['gender']) ?></td>
                            <td><?= htmlspecialchars($admin['email']) ?></td>
                            <td><?= htmlspecialchars($admin['phone']) ?></td>
                            <td>
                                <?= $admin['super_admin'] == 1
                                    ? '<span title="Super Admin" style="color:#ff9800;font-size:1.1em;">👑</span>'
                                    : '<span style="color:#888;">No</span>' ?>
                            </td>
                            <td><?= htmlspecialchars($admin['country']) ?></td>
                            <td><?= htmlspecialchars($admin['created_at']) ?></td>

                            <?php if ($_SESSION['super_admin'] == 1 && $currentIsProtected == 1): ?>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= BASE_URL . '/admin/admins/update.php?id=' . $admin['id'] ?>" class="btn-edit">Edit</a>
                                        <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                            <form class="deleteAdminForm" onsubmit="return confirm('Delete this admin?')" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $admin['id'] ?>">
                                                <button type="submit" class="btn-delete">Delete</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script src="<?= BASE_URL ?>/vendor/jquery/jquery.min.js"></script>
<script>
$(document).ready(function () {
    <?php if (!empty($message)): ?>
        $('#showMessage')
            .text("<?= htmlspecialchars($message) ?>")
            .css({
                'background-color': '#e8f5e9',
                'color': 'green'
            })
            .slideDown();
        setTimeout(() => $('#showMessage').slideUp(), 3000);
    <?php endif; ?>

    $(document).on('submit', '.deleteAdminForm', function (e) {
        e.preventDefault();
        const form = $(this);

        $.ajax({
            url: '<?= BASE_URL ?>/admin/php_files/sections/admins/delete.php',
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (res) {
                $('#showMessage')
                    .stop(true, true)
                    .hide()
                    .text(res.message)
                    .css({
                        'background-color': res.success ? '#e8f5e9' : '#ffebee',
                        'color': res.success ? 'green' : 'red'
                    })
                    .slideDown();

                if (res.success) {
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    setTimeout(() => $('#showMessage').slideUp(), 3000);
                }
            },
            error: function () {
                $('#showMessage')
                    .stop(true, true)
                    .hide()
                    .text('Failed to delete admin.')
                    .css({
                        'background-color': '#ffebee',
                        'color': 'red'
                    })
                    .slideDown();

                setTimeout(() => $('#showMessage').slideUp(), 3000);
            }
        });
    });
});
</script>

<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>
