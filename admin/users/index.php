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

require_once BASE_PATH . '/admin/includes/header_admin.php';
require_once BASE_PATH . '/admin/includes/sidebar_admin.php';

// Fetch users
$users = [];
$sql = "SELECT u.*, c.name AS city_name
        FROM users u
        LEFT JOIN cities c ON u.city_id = c.id
        ORDER BY u.created_at DESC";

$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Fetch cities for form dropdown (if needed)
$cities = [];
$cityRes = $conn->query("SELECT id, name FROM cities ORDER BY name ASC");
if ($cityRes) {
    while ($row = $cityRes->fetch_assoc()) {
        $cities[] = $row;
    }
}

// Session message for redirects
$message = '';
if (isset($_SESSION['admin_message'])) {
    $message = $_SESSION['admin_message'];
    unset($_SESSION['admin_message']);
}
?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/donors_admin.css' ?>">
</head>

<!-- Sliding Message Box -->
<div id="showMessage" style="display:none; position: fixed; top: 70px; left: 56%; transform: translateX(-56%);
    background-color: #e8f5e9; color: green; padding: 12px 25px; border-radius: 0 0 6px 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); z-index: 9999; font-weight: 500;">
</div>

<main class="content">
    <h2>User Management</h2>

    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="back-button">← Back</a>
    <a href="<?= BASE_URL ?>/admin/users/add_user.php" class="add-donor-btn">+ Add New User</a>

    <div class="scroll-table">
        <!-- User List Table -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) === 0): ?>
                    <tr>
                        <td colspan="9" style="text-align:center;">No users found.</td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1;
                    foreach ($users as $user): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['gender']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['phone']) ?></td>
                            <td><?= htmlspecialchars($user['city_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['country']) ?></td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= BASE_URL . '/admin/users/update.php?id=' . $user['id'] ?>" class="btn-edit">Edit</a>
                                    <form class="deleteUserForm" onsubmit="return confirm('Delete this user?')" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                        <button type="submit" class="btn-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script src="<?= BASE_URL ?>/vendor/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Show session message if exists
        <?php if (!empty($message)): ?>
            $('#showMessage')
                .text("<?= htmlspecialchars($message) ?>")
                .css({
                    'background-color': '#e8f5e9',
                    'color': 'green'
                })
                .slideDown();
            setTimeout(() => {
                $('#showMessage').slideUp();
            }, 3000);
        <?php endif; ?>

        // Delete User
        $(document).on('submit', '.deleteUserForm', function(e) {
            e.preventDefault();
            const form = $(this);

            $('#showMessage')
                .stop(true, true)
                .hide();

            $.ajax({
                url: '<?= BASE_URL ?>/admin/php_files/sections/users/delete.php',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(res) {
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
                        setTimeout(() => {
                            $('#showMessage').slideUp();
                        }, 3000);
                    }
                },
                error: function() {
                    $('#showMessage')
                        .stop(true, true)
                        .hide()
                        .text('Failed to delete user.')
                        .css({
                            'background-color': '#ffebee',
                            'color': 'red'
                        })
                        .slideDown();

                    setTimeout(() => {
                        $('#showMessage').slideUp();
                    }, 3000);
                }
            });
        });
    });
</script>

<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>