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

// Get donor ID from query string
$donorId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($donorId === 0) {
    echo "Invalid donor ID.";
    exit;
}

// Fetch donor info
$stmt = $conn->prepare("SELECT * FROM donors WHERE id = ?");
$stmt->bind_param("i", $donorId);
$stmt->execute();
$result = $stmt->get_result();
$donor = $result->fetch_assoc();
$stmt->close();

if (!$donor) {
    echo "Donor not found.";
    exit;
}

// Fetch cities
$cities = [];
$cityRes = $conn->query("SELECT id, name FROM cities ORDER BY name ASC");
if ($cityRes) {
    while ($row = $cityRes->fetch_assoc()) {
        $cities[] = $row;
    }
}

// Fetch blood groups
$bloodGroups = [];
$groupRes = $conn->query("SELECT id, name FROM blood_groups ORDER BY name ASC");
if ($groupRes) {
    while ($row = $groupRes->fetch_assoc()) {
        $bloodGroups[] = $row;
    }
}

require_once BASE_PATH . '/admin/includes/header_admin.php';
require_once BASE_PATH . '/admin/includes/sidebar_admin.php';
?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/add_donor_admin.css' ?>">
</head>



<main class="content">
    <!-- Sliding Message Box -->
    <div id="showMessage" style="display: none; position: fixed; top: 10; left: 56%; transform: translateX(-56%);
    background-color: #e8f5e9; color: green; padding: 12px 25px; border-radius: 0 0 6px 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); z-index: 9999; font-weight: 500;">
    </div>
    <h2>Edit Donor</h2>
    <a href="<?= BASE_URL ?>/admin/donors/index.php" class="back-button">&larr; Back to Donors List</a>

    <form action="<?= BASE_URL ?>/admin/php_files/sections/donors/update.php" method="POST" id="editDonorForm" style="max-width: 600px;">
        <input type="hidden" name="id" value="<?= $donor['id'] ?>">

        <div style="margin-bottom: 15px;">
            <input type="text" name="name" value="<?= htmlspecialchars($donor['name']) ?>" required>
        </div>

        <div style="margin-bottom: 15px;">
            <select name="gender" required>
                <option value="" disabled>Select Gender</option>
                <option value="Male" <?= $donor['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $donor['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $donor['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="email" name="email" value="<?= htmlspecialchars($donor['email']) ?>" required>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="phone" value="<?= htmlspecialchars($donor['phone']) ?>" required>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="password" name="password" placeholder="New Password (optional)">
        </div>


        <div style="margin-bottom: 15px;">
            <textarea name="address" rows="3" required><?= htmlspecialchars($donor['address']) ?></textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="pin_code" value="<?= htmlspecialchars($donor['pin_code']) ?>" required>
        </div>

        <div style="margin-bottom: 15px;">
            <select name="city_id" required>
                <option value="" disabled>Select City</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?= $city['id'] ?>" <?= $city['id'] == $donor['city_id'] ? 'selected' : '' ?>><?= htmlspecialchars($city['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="country" value="<?= htmlspecialchars($donor['country']) ?>" required>
        </div>

        <div style="margin-bottom: 15px;">
            <select name="blood_group_id" required>
                <option value="" disabled>Select Blood Group</option>
                <?php foreach ($bloodGroups as $group): ?>
                    <option value="<?= $group['id'] ?>" <?= $group['id'] == $donor['blood_group_id'] ? 'selected' : '' ?>><?= htmlspecialchars($group['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Update Donor</button>
    </form>
</main>


<script>
    $(document).ready(function() {
        const form = $('#editDonorForm');
        const showMessage = $('#showMessage');

        form.on('submit', function(e) {
            e.preventDefault();

            const submitButton = form.find('button[type="submit"]');
            submitButton.prop('disabled', true).text('Updating...');

            $.ajax({
                url: form.attr('action'),
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

                    // Hide after 3 seconds
                    setTimeout(() => {
                        showMessage.slideUp();
                    }, 3000);

                    if (res.success) {
                        setTimeout(() => {
                            window.location.href = "<?= BASE_URL ?>/admin/donors/index.php";
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

                    setTimeout(() => {
                        showMessage.slideUp();
                    }, 3000);
                },
                complete: function() {
                    submitButton.prop('disabled', false).text('Update Donor');
                }
            });
        });
    });
</script>


<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>