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

// Fetch blood groups with MySQLi
$sql = "SELECT * FROM blood_groups ORDER BY name ASC";
$result = $conn->query($sql);

$bloodGroups = [];
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $bloodGroups[] = $row;
  }
}
?>

<head>
  <link rel="stylesheet" href="<?= BASE_URL . '/admin/assets/css/blood_groups_admin.css' ?>">
</head>

<main class="content">
    <h2>Blood Group Management</h2>
    <!-- back button -->
    <a href="<?= BASE_URL ?>/admin/dashboard.php" style="display: inline-block; margin-bottom: 15px; padding: 8px 12px; background-color: #ccc; color: #000; text-decoration: none; border-radius: 4px;">
      ← Back
    </a>


    <form id="addBloodGroupForm" style="margin-bottom: 20px;">
      <input type="text" name="name" placeholder="Enter blood group (e.g., A+)" required>
      <button type="submit">Add Blood Group</button>
    </form>

    <div class="scroll-table">
      <table border="1" cellpadding="10" cellspacing="0">
        <thead>
          <tr>
            <th>#</th>
            <th>Group Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($bloodGroups) === 0): ?>
            <tr>
              <td colspan="3" style="text-align:center; padding:20px; font-style:italic; color:#777;">
                No blood groups found.
              </td>
            </tr>
          <?php else: ?>
            <?php $serial = 1; ?>
            <?php foreach ($bloodGroups as $group): ?>
              <tr>
                <td><?= $serial++ ?></td>
                <td><?= htmlspecialchars($group['name']) ?></td>
                <td>
                  <button class="editBloodBtn" data-id="<?= $group['id'] ?>" data-name="<?= htmlspecialchars($group['name']) ?>">Edit</button>
                  <form class="deleteBloodGroupForm" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $group['id'] ?>">
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
    <!-- Edit Blood Group Modal -->
    <div id="editBloodGroupModal" style="display: none; margin-top: 20px;">
      <form id="editBloodGroupForm">
        <input type="hidden" name="id" id="editBloodId">
        <input type="text" name="name" id="editBloodName" required>
        <button type="submit">Update Blood Group</button>
        <button type="button" id="cancelEditBlood">Cancel</button>
      </form>
    </div>

</main>

<script>
  // -- Add blood group handler
  $(document).ready(function() {
    $('#addBloodGroupForm').on('submit', function(e) {
      e.preventDefault();

      const form = $(this);
      const formData = form.serialize();
      const messageBox = $('#messageBox');

      $.ajax({
        url: '<?= BASE_URL . '/admin/php_files/sections/blood_groups/add.php' ?>',
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
          } else {

          }

          setTimeout(() => {
            messageBox.fadeOut('slow', function() {
              $(this).text('').removeAttr('style').show();
            });
          }, 3000);
        },
        error: function(xhr, status, error) {
          let errorMessage = 'Something went wrong.';
          try {
            const response = JSON.parse(xhr.responseText);
            if (response.message) {
              errorMessage = response.message;
            }
          } catch (e) {
            console.warn('Response is not valid JSON:', xhr.responseText);
          }

          messageBox.text(errorMessage)
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

  // -- Delete blood group handler
  $(document).on('submit', '.deleteBloodGroupForm', function(e) {
    e.preventDefault();
    if (!confirm('Delete this group?')) return false;

    const form = $(this);
    const formData = form.serialize();
    const messageBox = $('#messageBox');

    $.ajax({
      url: '<?= BASE_URL . '/admin/php_files/sections/blood_groups/delete.php' ?>',
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

  // -- Edit blood group handler
  // Open edit modal with pre-filled data
  $(document).on('click', '.editBloodBtn', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');

    $('#editBloodId').val(id);
    $('#editBloodName').val(name);
    $('#editBloodGroupModal').slideDown();
  });

  // Cancel edit
  $('#cancelEditBlood').on('click', function() {
    $('#editBloodGroupModal').slideUp();
    $('#editBloodGroupForm')[0].reset();
  });

  // Submit edit form
  $('#editBloodGroupForm').on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize();
    const messageBox = $('#messageBox');

    $.ajax({
      url: '<?= BASE_URL . '/admin/php_files/sections/blood_groups/update.php' ?>',
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
          $('#editBloodGroupModal').slideUp();
          $('#editBloodGroupForm')[0].reset();
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
</script>



<?php require_once BASE_PATH . '/admin/includes/footer_admin.php'; ?>