<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . BASE_URL . '/admin/loginPage_admin.php');
    exit;
}

// Fetch cities
$cities = [];
$cityResult = $conn->query("SELECT id, name FROM cities ORDER BY name ASC");
if ($cityResult) {
    while ($row = $cityResult->fetch_assoc()) {
        $cities[] = $row;
    }
}

// Fetch blood groups
$bloodGroups = [];
$groupResult = $conn->query("SELECT id, name FROM blood_groups ORDER BY name ASC");
if ($groupResult) {
    while ($row = $groupResult->fetch_assoc()) {
        $bloodGroups[] = $row;
    }
}
?>

<?php require_once BASE_PATH . '/admin/includes/header_admin.php'; ?>
<?php require_once BASE_PATH . '/admin/includes/sidebar_admin.php'; ?>

<head>
    <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/add_donor_admin.css' ?>">
</head>

<main class="content">

    <!-- Sliding Message Box -->
    <div id="showMessage" style="display:none; position: fixed; top: 70px; left: 56%; transform: translateX(-56%);
        background-color: #e8f5e9; color: green; padding: 12px 25px; border-radius: 0 0 6px 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); z-index: 9999; font-weight: 500;">
    </div>

    
    <a href="<?= BASE_URL ?>/admin/donors/index.php" style="display: inline-block; margin-bottom: 15px; padding: 8px 12px; background-color: #ccc; color: #000; text-decoration: none; border-radius: 4px;">
        ← Back to Donors List
    </a>
    <h2 style="text-align: center;">Add Donor</h2>

    <form id="addDonor" style="max-width: 600px;">
        <div style="margin-bottom: 15px;">
            <input type="text" name="name" placeholder="Full Name" required>
        </div>

        <div style="margin-bottom: 15px;">
            <select name="gender" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="email" name="email" placeholder="Email Address" required>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="phone" placeholder="Phone Number" required>
        </div>

        <div style="margin-bottom: 15px;">
            <textarea name="address" placeholder="Full Address" rows="3" required></textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="pin_code" placeholder="PIN Code" required>
        </div>

        <div style="margin-bottom: 15px;">
            <select name="city_id" required>
                <option value="" selected disabled>Select City</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?= $city['id'] ?>"><?= htmlspecialchars($city['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="text" name="country" placeholder="Country" required>
        </div>

        <div style="margin-bottom: 15px;">
            <select name="blood_group_id" required>
                <option value="" selected disabled>Select Blood Group</option>
                <?php foreach ($bloodGroups as $group): ?>
                    <option value="<?= $group['id'] ?>"><?= htmlspecialchars($group['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <input type="password" name="password" placeholder="Password (min 6 chars)" required>
        </div>

        <button type="submit">Add Donor</button>
    </form>

</main>

<script>
$(document).ready(function () {
    const form = $('#addDonor');
    const showMessage = $('#showMessage');
    const submitBtn = form.find('button[type="submit"]');

    form.on('submit', function (e) {
        e.preventDefault();

        submitBtn.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: '<?= BASE_URL . '/admin/php_files/sections/donors/add.php' ?>',
            type: 'POST',
            data: form.serialize(),
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
                        window.location.href = "<?= BASE_URL ?>/admin/donors/index.php";
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
                submitBtn.prop('disabled', false).text('Add Donor');
            }
        });
    });
});
</script>

<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>
