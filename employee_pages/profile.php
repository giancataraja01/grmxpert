<?php
// employee_pages/profile.php

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../db.php';

if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'employee') {
    echo "<div class='text-red-600'>Access denied.</div>";
    return;
}

$user_id = (int)$_SESSION['user']['id'];
$message = '';
$type = 'success';

// Load current profile
$stmt = $conn->prepare("SELECT full_name, email, employee_id, department, contact_no FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$current) {
    echo "<div class='text-red-600'>User not found.</div>";
    return;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name   = trim($_POST['full_name'] ?? '');
    $employee_id = trim($_POST['employee_id'] ?? '');
    $department  = trim($_POST['department'] ?? '');
    $contact_no  = trim($_POST['contact_no'] ?? '');

    if ($full_name === '') {
        $message = "Full name is required.";
        $type = 'error';
    } else {
        $upd = $conn->prepare("
            UPDATE users
            SET full_name = ?, employee_id = ?, department = ?, contact_no = ?
            WHERE id = ? AND role = 'employee'
        ");
        $upd->bind_param("ssssi", $full_name, $employee_id, $department, $contact_no, $user_id);

        if ($upd->execute()) {
            // update session name
            $_SESSION['user']['fullname'] = $full_name;

            header("Location: ../employee_dashboard.php?page=profile&success=" . urlencode("Profile updated successfully."));
            exit;
        } else {
            $message = "Update failed: " . $upd->error;
            $type = 'error';
        }
        $upd->close();
    }
}
?>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
  <h2 class="text-2xl font-semibold mb-1">Edit Profile</h2>
  <p class="text-sm text-gray-600 mb-5">Update your personal information.</p>

  <?php if (!empty($message)): ?>
    <div class="mb-4 p-3 rounded <?= $type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="POST" class="space-y-4">
    <div>
      <label class="block text-sm font-medium text-gray-700">Full Name *</label>
      <input name="full_name" class="mt-1 w-full border rounded-lg p-3"
             value="<?= htmlspecialchars($current['full_name'] ?? '') ?>" required>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Email (readonly)</label>
      <input class="mt-1 w-full border rounded-lg p-3 bg-gray-50"
             value="<?= htmlspecialchars($current['email'] ?? '') ?>" readonly>
      <div class="text-xs text-gray-500 mt-1">Ask HR/Admin if you need to change your email.</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Employee ID</label>
        <input name="employee_id" class="mt-1 w-full border rounded-lg p-3"
               value="<?= htmlspecialchars($current['employee_id'] ?? '') ?>">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Department</label>
        <input name="department" class="mt-1 w-full border rounded-lg p-3"
               value="<?= htmlspecialchars($current['department'] ?? '') ?>">
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Contact No</label>
      <input name="contact_no" class="mt-1 w-full border rounded-lg p-3"
             value="<?= htmlspecialchars($current['contact_no'] ?? '') ?>">
    </div>

    <div class="pt-2 flex justify-end">
      <button type="submit"
              class="px-5 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700">
        Save Changes
      </button>
    </div>
  </form>
</div>
