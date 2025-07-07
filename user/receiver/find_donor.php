<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once BASE_PATH . '/config/db.php';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
  header('Location: ' . BASE_URL . '/user/login.php');
  exit;
}

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$userId = $_SESSION['user_id'];
$isDonor = isset($_SESSION['is_donor']) && $_SESSION['is_donor'] == 1;
$name = $_SESSION['user_name'] ?? 'User';

$selectedGroupId = $_GET['blood_group_id'] ?? '';
$selectedCityId = $_GET['city_id'] ?? '';

$results = [];

// Fetch dropdown options
$cityOptions = $conn->query("SELECT id, name FROM cities ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$bloodGroupOptions = $conn->query("SELECT id, name FROM blood_groups ORDER BY name")->fetch_all(MYSQLI_ASSOC);

// Get user's city_id
$userCityId = null;
$userCityStmt = $conn->prepare("SELECT city_id FROM users WHERE id = ?");
$userCityStmt->bind_param("i", $userId);
$userCityStmt->execute();
$userCityStmt->bind_result($userCityId);
$userCityStmt->fetch();
$userCityStmt->close();

// Build query
$query = "
    SELECT d.name, d.phone, d.gender, c.name AS city, b.name AS blood_group, d.status
    FROM donors d
    LEFT JOIN cities c ON d.city_id = c.id
    LEFT JOIN blood_groups b ON d.blood_group_id = b.id
    WHERE 1
    ";
    // WHERE d.status = 'Active'

$params = [];
$types = '';

// Apply filters
if ($selectedGroupId) {
  $query .= " AND d.blood_group_id = ?";
  $params[] = $selectedGroupId;
  $types .= 'i';
}

if ($selectedCityId) {
  $query .= " AND d.city_id = ?";
  $params[] = $selectedCityId;
  $types .= 'i';
} elseif (!$selectedGroupId && $userCityId) {
  // Default to user's city if no filter selected
  $query .= " AND d.city_id = ?";
  $params[] = $userCityId;
  $types .= 'i';
}

$stmt = $conn->prepare($query);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<?php include BASE_PATH . '/includes/header.php'; ?>
<?php include BASE_PATH . '/includes/sidebar.php'; ?>

<head>
  <link rel="stylesheet" href="<?= BASE_URL ?>/user/assets/css/dashboard.css">
  <link rel="stylesheet" href="<?= BASE_URL . '/user/assets/css/find_donor.css' ?>">

  <style>
    .donor-status {
      display: inline-block;
      margin-left: 10px;
      padding: 2px 12px;
      border-radius: 12px;
      font-size: 0.85em;
      font-weight: 600;
      color: #fff;
      vertical-align: middle;
      letter-spacing: 0.5px;
      text-transform: capitalize;
      transition: background 0.2s;
    }

    .donor-status.active {
      background: #43a047;
      /* Green for active */
    }

    .donor-status.inactive {
      background: #b71c1c;
      /* Red for inactive */
    }
  </style>

</head>

<main class="content">
  <a href="<?= BASE_URL ?>/user/dashboard.php" style="display: inline-block; margin-bottom: 15px; padding: 8px 12px; background-color: #ccc; color: #000; text-decoration: none; border-radius: 4px;">
    Back
  </a>
  <h2>Find Donors</h2>

  <form method="GET" class="search-form">
    <select name="blood_group_id">
      <option value="">Select Blood Group</option>
      <?php foreach ($bloodGroupOptions as $bg): ?>
        <option value="<?= $bg['id'] ?>" <?= $selectedGroupId == $bg['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($bg['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="city_id">
      <option value="">Select City</option>
      <?php foreach ($cityOptions as $city): ?>
        <option value="<?= $city['id'] ?>" <?= $selectedCityId == $city['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($city['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button type="submit">Search</button>
  </form>

  <?php if (count($results)): ?>
    <div class="donor-result">
      <h3>
        <?php if ($selectedCityId || $selectedGroupId): ?>
          Search Results (<?= count($results) ?> found)
        <?php else: ?>
          Donors from your city (<?= count($results) ?> found)
        <?php endif; ?>
      </h3>

      <?php foreach ($results as $donor): ?>
        <div class="donor-card">
          <h4>
            <?= htmlspecialchars($donor['name']) ?> (<?= htmlspecialchars($donor['gender']) ?>)
            <span class="donor-status <?= strtolower($donor['status']) ?>">
              <?= htmlspecialchars($donor['status']) ?>
            </span>
          </h4>
          <p><strong>Blood Group:</strong> <?= htmlspecialchars($donor['blood_group']) ?></p>
          <p><strong>City:</strong> <?= htmlspecialchars($donor['city']) ?></p>
          <p><strong>Phone:</strong> <?= htmlspecialchars($donor['phone']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No donors found.</p>
  <?php endif; ?>

</main>

<?php include BASE_PATH . '/includes/footer.php'; ?>