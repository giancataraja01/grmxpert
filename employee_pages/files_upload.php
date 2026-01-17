<?php
// employee_pages/files_upload.php

// We are inside employee_dashboard.php (already session_start there).
// But to be safe:
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../db.php';

$user_id = (int)($_SESSION['user']['id'] ?? 0);

// Fetch uploads for this employee
$uploads = [];
if ($user_id > 0) {
    $stmt = $conn->prepare("
        SELECT id, original_name, stored_name, file_path, mime_type, file_size, description, status, created_at
        FROM employee_uploads
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $uploads[] = $row;
    }
    $stmt->close();
}

// helper: format bytes
function fmtBytes($bytes) {
    $bytes = (float)$bytes;
    if ($bytes <= 0) return "0 B";
    $units = ["B","KB","MB","GB","TB"];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units)-1) { $bytes /= 1024; $i++; }
    return round($bytes, 2) . " " . $units[$i];
}

function badgeClass($status) {
    return match ($status) {
        'approved'  => 'bg-green-100 text-green-700 border-green-200',
        'rejected'  => 'bg-red-100 text-red-700 border-red-200',
        'reviewed'  => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        default     => 'bg-gray-100 text-gray-700 border-gray-200', // submitted
    };
}
?>

<div class="bg-white rounded-2xl shadow p-6">
  <h2 class="text-2xl font-semibold">Upload Files</h2>
  <p class="mt-2 text-gray-600">Upload your documents here and view your submission history below.</p>

  <!-- ✅ STATUS MESSAGES -->
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

  <!-- ✅ UPLOAD FORM -->
  <form class="mt-6" method="POST" action="process_employee_upload.php" enctype="multipart/form-data">
    <div class="grid gap-4 md:grid-cols-3">
      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Choose file</label>
        <input type="file" name="file" class="w-full p-3 border rounded-lg bg-white" required>
        <p class="text-xs text-gray-500 mt-1">Allowed: pdf, doc, docx, jpg, jpeg, png (max 10MB)</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
        <input type="text" name="description" placeholder="e.g., Medical certificate"
               class="w-full p-3 border rounded-lg">
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700">
        Upload
      </button>
    </div>
  </form>

  <!-- ✅ UPLOAD HISTORY -->
  <div class="mt-10">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-semibold">My Uploaded Files</h3>
      <div class="text-sm text-gray-500"><?= count($uploads) ?> file(s)</div>
    </div>

    <?php if (empty($uploads)): ?>
      <div class="mt-4 p-4 bg-gray-50 border rounded-lg text-gray-600">
        No uploads yet. Upload your first document above.
      </div>
    <?php else: ?>
      <div class="mt-4 overflow-x-auto border rounded-xl">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-700">
            <tr>
              <th class="text-left p-3">File</th>
              <th class="text-left p-3">Description</th>
              <th class="text-left p-3">Size</th>
              <th class="text-left p-3">Status</th>
              <th class="text-left p-3">Uploaded</th>
              <th class="text-left p-3">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php foreach ($uploads as $u): ?>
              <tr class="hover:bg-gray-50">
                <td class="p-3">
                  <div class="font-medium text-gray-900">
                    <?= htmlspecialchars($u['original_name']) ?>
                  </div>
                  <div class="text-xs text-gray-500">
                    <?= htmlspecialchars($u['mime_type'] ?? '') ?>
                  </div>
                </td>
                <td class="p-3 text-gray-700">
                  <?= htmlspecialchars($u['description'] ?? '') ?>
                </td>
                <td class="p-3 text-gray-700">
                  <?= fmtBytes($u['file_size'] ?? 0) ?>
                </td>
                <td class="p-3">
                  <span class="inline-flex items-center px-2 py-1 border rounded-full text-xs <?= badgeClass($u['status']) ?>">
                    <?= htmlspecialchars($u['status']) ?>
                  </span>
                </td>
                <td class="p-3 text-gray-700">
                  <?= htmlspecialchars(date('M d, Y h:i A', strtotime($u['created_at']))) ?>
                </td>
                <td class="p-3">
                  <a
                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700"
                    href="download_employee_file.php?id=<?= (int)$u['id'] ?>"
                  >
                    Download
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
