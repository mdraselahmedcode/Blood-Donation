<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/admin/loginPage_admin.php');
    exit;
}

require_once BASE_PATH . '/admin/includes/header_admin.php';
require_once BASE_PATH . '/admin/includes/sidebar_admin.php';

// Fetch donors
$donors = [];
$sql = "SELECT d.*, c.name AS city_name, b.name AS blood_group_name
        FROM donors d
        LEFT JOIN cities c ON d.city_id = c.id
        LEFT JOIN blood_groups b ON d.blood_group_id = b.id
        ORDER BY d.created_at DESC";

$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $donors[] = $row;
    }
}

// Fetch cities for form dropdown
$cities = [];
$cityRes = $conn->query("SELECT id, name FROM cities ORDER BY name ASC");
if ($cityRes) {
    while ($row = $cityRes->fetch_assoc()) {
        $cities[] = $row;
    }
}
?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/donors_admin.css' ?>">
</head>

<main class="content">
    <h2>Donor Management</h2>

    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="back-button">← Back</a>
    <a href="<?= BASE_URL ?>/admin/donors/add_donor.php" class="add-donor-btn">+ Add New Donor</a>


    <!-- Success / Error Message -->
    <div id="messageBox" style="margin-bottom: 15px;"></div>

    <div class="scroll-table">
        <!-- Donor List Table -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Blood Group</th>
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
                <?php if (count($donors) === 0): ?>
                    <tr>
                        <td colspan="10" style="text-align:center;">No donors found.</td>
                    </tr>
                <?php else: ?>
                    <?php $i = 1;
                    foreach ($donors as $donor): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($donor['name']) ?></td>
                            <td><?= htmlspecialchars($donor['blood_group_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($donor['gender']) ?></td>
                            <td><?= htmlspecialchars($donor['email']) ?></td>
                            <td><?= htmlspecialchars($donor['phone']) ?></td>
                            <td><?= htmlspecialchars($donor['city_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($donor['country']) ?></td>
                            <td><?= htmlspecialchars($donor['created_at']) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= BASE_URL . '/admin/donors/update.php?id=' . $donor['id'] ?>" class="btn-edit">Edit</a>

                                    <form class="deleteDonorForm" onsubmit="return confirm('Delete this donor?')" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $donor['id'] ?>">
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

<script>
    $(document).ready(function() {
        // Add Donor
        // $('#addDonorForm').on('submit', function (e) {
        //     e.preventDefault();
        //     const form = $(this);
        //     const messageBox = $('#messageBox');

        //     $.ajax({
        //         url: '<?= BASE_URL ?>/admin/php_files/sections/donors/add.php',
        //         type: 'POST',
        //         data: form.serialize(),
        //         dataType: 'json',
        //         success: function (res) {
        //             messageBox.text(res.message)
        //                 .css({
        //                     padding: '10px',
        //                     borderRadius: '5px',
        //                     color: res.success ? 'green' : 'red',
        //                     backgroundColor: res.success ? '#e8f5e9' : '#ffebee'
        //                 });

        //             if (res.success) {
        //                 form[0].reset();
        //                 setTimeout(() => window.location.reload(), 1000);
        //             }

        //             setTimeout(() => {
        //                 messageBox.fadeOut('slow', function () {
        //                     $(this).text('').removeAttr('style').show();
        //                 });
        //             }, 3000);
        //         },
        //         error: function () {
        //             messageBox.text('Failed to add donor.')
        //                 .css({
        //                     padding: '10px',
        //                     borderRadius: '5px',
        //                     color: 'red',
        //                     backgroundColor: '#ffebee'
        //                 });
        //         }
        //     });
        // });

        // Delete Donor
        $(document).on('submit', '.deleteDonorForm', function(e) {
            e.preventDefault();
            const form = $(this);
            const messageBox = $('#messageBox');

            $.ajax({
                url: '<?= BASE_URL ?>/admin/php_files/sections/donors/delete.php',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(res) {
                    messageBox.text(res.message)
                        .css({
                            padding: '10px',
                            borderRadius: '5px',
                            color: res.success ? 'green' : 'red',
                            backgroundColor: res.success ? '#e8f5e9' : '#ffebee'
                        });

                    if (res.success) {
                        setTimeout(() => window.location.reload(), 1000);
                    }
                },
                error: function() {
                    messageBox.text('Failed to delete donor.')
                        .css({
                            padding: '10px',
                            borderRadius: '5px',
                            color: 'red',
                            backgroundColor: '#ffebee'
                        });
                }
            });
        });
    });
</script>

<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>