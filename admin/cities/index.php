<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}

require_once BASE_PATH . '/admin/includes/header_admin.php';
require_once BASE_PATH . '/admin/includes/sidebar_admin.php';

// Fetch cities
$sql = "SELECT * FROM cities ORDER BY name ASC";
$result = $conn->query($sql);

$cities = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row;
    }
}
?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/blood_groups_admin.css' ?>">
</head>

<main class="content">
    <h2>City Management</h2>
    <!-- back button -->
    <a href="<?= BASE_URL ?>/admin/dashboard.php" style="display: inline-block; margin-bottom: 15px; padding: 8px 12px; background-color: #ccc; color: #000; text-decoration: none; border-radius: 4px;">
        ← Back
    </a>

    <form id="addCityForm" style="margin-bottom: 20px;">
        <input type="text" name="name" placeholder="Enter city name" required>
        <button type="submit">Add City</button>
    </form>
    <div class="scroll-table">
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>City Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($cities) === 0): ?>
                    <tr>
                        <td colspan="3" style="text-align:center; padding:20px; font-style:italic; color:#777;">
                            No cities found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $serial = 1; ?>
                    <?php foreach ($cities as $city): ?>
                        <tr>
                            <td><?= $serial++ ?></td>
                            <td><?= htmlspecialchars($city['name']) ?></td>
                            <td>
                                <button class="editCityBtn" data-id="<?= $city['id'] ?>" data-name="<?= htmlspecialchars($city['name']) ?>">Edit</button>

                                <form class="deleteCityForm" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $city['id'] ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- showMessage div -->
    <div id="messageBox" style="margin-bottom: 15px; margin-top: 15px;"></div>

    <!-- Edit City Modal -->
    <div id="editCityModal" style="display: none; margin-bottom: 20px;">
        <form id="editCityForm">
            <input type="hidden" name="id" id="editCityId">
            <input type="text" name="name" id="editCityName" required>
            <button type="submit">Update City</button>
            <button type="button" id="cancelEdit">Cancel</button>
        </form>
    </div>

</main>

<script>
    $(document).ready(function() {
        // -- Add city
        $('#addCityForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const formData = form.serialize();
            const messageBox = $('#messageBox');

            $.ajax({
                url: '<?= BASE_URL . '/admin/php_files/sections/cities/add.php' ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    messageBox.text(res.message)
                        .css({
                            'padding': '10px',
                            'border-radius': '5px',
                            'color': res.success ? 'green' : 'red',
                            'background-color': res.success ? '#e8f5e9' : '#ffebee'
                        });

                    if (res.success) {
                        form[0].reset();
                        setTimeout(() => window.location.reload(), 1000);
                    }

                    setTimeout(() => {
                        messageBox.fadeOut('slow', function() {
                            $(this).text('').removeAttr('style').show();
                        });
                    }, 3000);
                },
                error: function() {
                    messageBox.text('Something went wrong.')
                        .css({
                            'padding': '10px',
                            'border-radius': '5px',
                            'color': 'red',
                            'background-color': '#ffebee'
                        });
                    setTimeout(() => {
                        messageBox.fadeOut('slow', function() {
                            $(this).text('').removeAttr('style').show();
                        });
                    }, 3000);
                }
            });
        });

        // -- Delete city
        $(document).on('submit', '.deleteCityForm', function(e) {
            e.preventDefault();
            if (!confirm('Delete this city?')) return false;

            const form = $(this);
            const formData = form.serialize();
            const messageBox = $('#messageBox');

            $.ajax({
                url: '<?= BASE_URL . '/admin/php_files/sections/cities/delete.php' ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    messageBox.text(res.message)
                        .css({
                            'padding': '10px',
                            'border-radius': '5px',
                            'color': res.success ? 'green' : 'red',
                            'background-color': res.success ? '#e8f5e9' : '#ffebee'
                        });

                    if (res.success) {
                        setTimeout(() => window.location.reload(), 1000);
                    }

                    setTimeout(() => {
                        messageBox.fadeOut('slow', function() {
                            $(this).text('').removeAttr('style').show();
                        });
                    }, 3000);
                },
                error: function() {
                    messageBox.text('Delete failed. Please try again.')
                        .css({
                            'padding': '10px',
                            'border-radius': '5px',
                            'color': 'red',
                            'background-color': '#ffebee'
                        });
                    setTimeout(() => {
                        messageBox.fadeOut('slow', function() {
                            $(this).text('').removeAttr('style').show();
                        });
                    }, 3000);
                }
            });
        });

        // -- Edit city
        // Show edit form with current data
        $(document).on('click', '.editCityBtn', function() {
            const cityId = $(this).data('id');
            const cityName = $(this).data('name');

            $('#editCityId').val(cityId);
            $('#editCityName').val(cityName);
            $('#editCityModal').slideDown();
        });

        // Cancel edit
        $('#cancelEdit').on('click', function() {
            $('#editCityModal').slideUp();
            $('#editCityForm')[0].reset();
        });

        // Handle update form submission
        $('#editCityForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            const messageBox = $('#messageBox');

            $.ajax({
                url: '<?= BASE_URL . '/admin/php_files/sections/cities/update.php' ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    messageBox.text(res.message)
                        .css({
                            'padding': '10px',
                            'border-radius': '5px',
                            'color': res.success ? 'green' : 'red',
                            'background-color': res.success ? '#e8f5e9' : '#ffebee'
                        });

                    if (res.success) {
                        $('#editCityModal').slideUp();
                        $('#editCityForm')[0].reset();
                        setTimeout(() => location.reload(), 1000);
                    }

                    setTimeout(() => {
                        messageBox.fadeOut('slow', function() {
                            $(this).text('').removeAttr('style').show();
                        });
                    }, 3000);
                },
                error: function() {
                    messageBox.text('Update failed. Please try again.')
                        .css({
                            'padding': '10px',
                            'border-radius': '5px',
                            'color': 'red',
                            'background-color': '#ffebee'
                        });
                    setTimeout(() => {
                        messageBox.fadeOut('slow', function() {
                            $(this).text('').removeAttr('style').show();
                        });
                    }, 3000);
                }
            });
        });

    });
</script>

<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>