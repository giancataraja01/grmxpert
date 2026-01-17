<?php
// admin_pages/leave_requests.php

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../db.php';

// Only admin or dept_head should access
if (
    empty($_SESSION['user']) ||
    !in_array($_SESSION['user']['role'], ['admin','dept_head'])
) {
    echo "<div class='text-red-600'>Access denied.</div>";
    return;
}

// Fetch leave requests with employee + approver names
$requests = [];
$sql = "
    SELECT
        lr.id,
        lr.date_from,
        lr.date_to,
        lr.leave_type,
        lr.reason,
        lr.status,
        lr.created_at,
        lr.approved_at,
        e.full_name AS employee_name,
        a.full_name AS approved_by_name
    FROM leave_requests lr
    JOIN users e ON e.id = lr.user_id
    LEFT JOIN users a ON a.id = lr.approved_by
    ORDER BY lr.created_at DESC
";

$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

function badgeClassLeaveAdmin($status) {
    return match ($status) {
        'approved' => 'bg-green-100 text-green-700 border-green-200',
        'rejected' => 'bg-red-100 text-red-700 border-red-200',
        default    => 'bg-yellow-100 text-yellow-800 border-yellow-200', // pending
    };
}
?>

<div class="bg-white rounded-lg shadow p-6">
  <h2 class="text-2xl font-semibold mb-4">Leave Requests</h2>

  <!-- ✅ STATUS MESSAGES -->
  <?php if (!empty($_GET['error'])): ?>
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
      <?= htmlspecialchars($_GET['error']) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($_GET['success'])): ?>
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded">
      <?= htmlspecialchars($_GET['success']) ?>
    </div>
  <?php endif; ?>

  <?php if (empty($requests)): ?>
    <div class="p-4 bg-gray-50 border rounded text-gray-600">
      No leave requests found.
    </div>
  <?php else: ?>
    <div class="space-y-4">
      <?php foreach ($requests as $r): ?>
        <div class="p-4 border rounded-lg hover:bg-gray-50">

          <!-- Header -->
          <div class="flex justify-between items-start">
            <div>
              <div class="font-semibold text-gray-900">
                <?= htmlspecialchars($r['employee_name']) ?>
              </div>
              <div class="text-sm text-gray-600">
                <?= htmlspecialchars($r['leave_type']) ?> |
                <?= htmlspecialchars(date('M d, Y', strtotime($r['date_from']))) ?>
                –
                <?= htmlspecialchars(date('M d, Y', strtotime($r['date_to']))) ?>
              </div>
            </div>

            <span class="inline-flex items-center px-2 py-1 border rounded-full text-xs <?= badgeClassLeaveAdmin($r['status']) ?>">
              <?= htmlspecialchars($r['status']) ?>
            </span>
          </div>

          <!-- Reason -->
          <div class="mt-2 text-gray-700">
            <strong>Reason:</strong>
            <?= htmlspecialchars($r['reason']) ?>
          </div>

          <!-- Meta -->
          <div class="mt-2 text-xs text-gray-500">
            Submitted:
            <?= htmlspecialchars(date('M d, Y h:i A', strtotime($r['created_at']))) ?>
            <?php if (!empty($r['approved_by_name'])): ?>
              | Approved by: <?= htmlspecialchars($r['approved_by_name']) ?>
              | <?= htmlspecialchars(date('M d, Y h:i A', strtotime($r['approved_at']))) ?>
            <?php endif; ?>
          </div>

          <!-- Actions -->
          <div class="mt-4 flex gap-2">
            <?php if ($r['status'] === 'pending'): ?>
              <form method="POST" action="process_leave_approval.php">
                <input type="hidden" name="leave_id" value="<?= (int)$r['id'] ?>">
                <input type="hidden" name="action" value="approve">
                <button type="submit"
                        class="px-4 py-1.5 bg-green-600 text-white rounded hover:bg-green-700">
                  Approve
                </button>
              </form>

              <form method="POST" action="process_leave_approval.php">
                <input type="hidden" name="leave_id" value="<?= (int)$r['id'] ?>">
                <input type="hidden" name="action" value="reject">
                <button type="submit"
                        class="px-4 py-1.5 bg-red-600 text-white rounded hover:bg-red-700">
                  Reject
                </button>
              </form>
            <?php else: ?>
              <div class="text-sm text-gray-500 italic">
                Action completed
              </div>
            <?php endif; ?>
          </div>

        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
