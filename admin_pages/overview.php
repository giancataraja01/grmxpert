<?php
// admin_pages/overview.php

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../db.php';

// ✅ Count total employees (users with role = employee)
$total_employees = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'employee'");
if ($res) {
    $row = $res->fetch_assoc();
    $total_employees = (int)$row['total'];
}

// (Optional) Count pending leave requests for dashboard display
$pending_leaves = 0;
$res2 = $conn->query("SELECT COUNT(*) AS total FROM leave_requests WHERE status = 'pending'");
if ($res2) {
    $row2 = $res2->fetch_assoc();
    $pending_leaves = (int)$row2['total'];
}
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

  <!-- ✅ TOTAL EMPLOYEES -->
  <div class="bg-white rounded-xl shadow p-6">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-sm text-gray-500">Total Employees</div>
        <div class="mt-2 text-3xl font-bold text-gray-900">
          <?php echo $total_employees; ?>
        </div>
      </div>
      <div class="text-4xl">👥</div>
    </div>
  </div>

  <!-- ✅ PENDING LEAVE REQUESTS -->
  <div class="bg-white rounded-xl shadow p-6">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-sm text-gray-500">Pending Leave Requests</div>
        <div class="mt-2 text-3xl font-bold text-gray-900">
          <?php echo $pending_leaves; ?>
        </div>
      </div>
      <div class="text-4xl">📝</div>
    </div>
  </div>

  <!-- Placeholder Card -->
  <div class="bg-white rounded-xl shadow p-6">
    <div class="text-sm text-gray-500">System Status</div>
    <div class="mt-2 text-lg font-semibold text-green-600">
      Operational
    </div>
  </div>

  <!-- Placeholder Card -->
  <div class="bg-white rounded-xl shadow p-6">
    <div class="text-sm text-gray-500">Last Login</div>
    <div class="mt-2 text-lg font-semibold text-gray-700">
      <?= date('M d, Y h:i A') ?>
    </div>
  </div>

</div>
