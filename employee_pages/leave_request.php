<?php
// employee_pages/leave_request.php

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../db.php';

$user_id = (int)($_SESSION['user']['id'] ?? 0);

// Fetch this employee's leave requests (with approver name)
$leaves = [];
if ($user_id > 0) {
    $stmt = $conn->prepare("
        SELECT
            lr.id,
            lr.date_from,
            lr.date_to,
            lr.leave_type,
            lr.reason,
            lr.status,
            lr.approved_at,
            a.full_name AS approved_by_name,
            lr.created_at
        FROM leave_requests lr
        LEFT JOIN users a ON a.id = lr.approved_by
        WHERE lr.user_id = ?
        ORDER BY lr.created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $leaves[] = $row;
    }
    $stmt->close();
}

function badgeClassLeave($status) {
    return match ($status) {
        'approved' => 'bg-green-100 text-green-700 border-green-200',
        'rejected' => 'bg-red-100 text-red-700 border-red-200',
        default    => 'bg-yellow-100 text-yellow-800 border-yellow-200', // pending
    };
}
?>

<div class="bg-white rounded-2xl shadow p-6">
  <h2 class="text-2xl font-semibold">File Leave Request</h2>
  <p class="mt-2 text-gray-600">Submit a leave request for approval.</p>

  <!-- ✅ STATUS / ERROR MESSAGES -->
  <?php if (!empty($_GET['error'])): ?>
    <div class="mt-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
      <?= htmlspecialchars($_GET['error']) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($_GET['success'])): ?>
    <div class="mt-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded">
      <?= htmlspecialchars($_GET['success']) ?>
    </div>
  <?php endif; ?>

  <!-- ✅ LEAVE REQUEST FORM -->
  <form class="mt-6" method="POST" action="process_leave_request.php">
    <div class="grid md:grid-cols-2 gap-4">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
        <input type="date" name="date_from" class="w-full p-3 border rounded-lg" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
        <input type="date" name="date_to" class="w-full p-3 border rounded-lg" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
        <select name="leave_type" class="w-full p-3 border rounded-lg" required>
          <option value="">Select Leave Type</option>
          <option value="Vacation">Vacation</option>
          <option value="Sick">Sick</option>
          <option value="Emergency">Emergency</option>
          <option value="Other">Other</option>
        </select>
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
        <textarea name="reason" rows="3" class="w-full p-3 border rounded-lg"
                  placeholder="Brief explanation of your leave request" required></textarea>
      </div>

    </div>

    <div class="mt-6">
      <button type="submit"
              class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700">
        Submit Leave Request
      </button>
    </div>
  </form>

  <!-- ✅ MY LEAVE REQUESTS LIST -->
  <div class="mt-10">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-semibold">My Leave Requests</h3>
      <div class="text-sm text-gray-500"><?= count($leaves) ?> request(s)</div>
    </div>

    <?php if (empty($leaves)): ?>
      <div class="mt-4 p-4 bg-gray-50 border rounded-lg text-gray-600">
        No leave requests yet.
      </div>
    <?php else: ?>
      <div class="mt-4 overflow-x-auto border rounded-xl">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-700">
            <tr>
              <th class="text-left p-3">Dates</th>
              <th class="text-left p-3">Type</th>
              <th class="text-left p-3">Reason</th>
              <th class="text-left p-3">Status</th>
              <th class="text-left p-3">Approved By</th>
              <th class="text-left p-3">Approved At</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php foreach ($leaves as $r): ?>
              <tr class="hover:bg-gray-50">
                <td class="p-3 text-gray-700">
                  <div class="font-medium">
                    <?= htmlspecialchars(date('M d, Y', strtotime($r['date_from']))) ?>
                    -
                    <?= htmlspecialchars(date('M d, Y', strtotime($r['date_to']))) ?>
                  </div>
                  <div class="text-xs text-gray-500">
                    Filed: <?= htmlspecialchars(date('M d, Y h:i A', strtotime($r['created_at']))) ?>
                  </div>
                </td>

                <td class="p-3 text-gray-700">
                  <?= htmlspecialchars($r['leave_type']) ?>
                </td>

                <td class="p-3 text-gray-700">
                  <?= htmlspecialchars($r['reason']) ?>
                </td>

                <td class="p-3">
                  <span class="inline-flex items-center px-2 py-1 border rounded-full text-xs <?= badgeClassLeave($r['status']) ?>">
                    <?= htmlspecialchars($r['status']) ?>
                  </span>
                </td>

                <td class="p-3 text-gray-700">
                  <?= htmlspecialchars($r['approved_by_name'] ?? '—') ?>
                </td>

                <td class="p-3 text-gray-700">
                  <?php if (!empty($r['approved_at'])): ?>
                    <?= htmlspecialchars(date('M d, Y h:i A', strtotime($r['approved_at']))) ?>
                  <?php else: ?>
                    —
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
