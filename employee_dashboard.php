<?php
// employee_dashboard.php
session_start();

require_once 'db.php';

// ✅ Access gating: only logged-in employees
if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'employee') {
    header("Location: login.php?error=" . urlencode("Access denied."));
    exit;
}

// Default avatar
$avatar_url = 'uploads/avatars/default.png';

// Fetch avatar if column exists (optional)
if (!empty($_SESSION['user']['id'])) {
    $user_id = (int)$_SESSION['user']['id'];

    // Safely try avatar column (prevents crash if column doesn't exist)
    $check = $conn->query("SHOW COLUMNS FROM users LIKE 'avatar'");
    if ($check && $check->num_rows > 0) {
        $stmt = $conn->prepare("SELECT avatar FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($avatar);
        $stmt->fetch();
        $stmt->close();

        $avatar_path = __DIR__ . "/uploads/avatars/" . $avatar;
        if (!empty($avatar) && file_exists($avatar_path)) {
            $avatar_url = "uploads/avatars/" . $avatar;
        }
    }
}

// Allowed employee pages (inside employee_pages/)
$allowed_pages = [
  'overview',
  'files_upload',
  'leave_request',
  'profile', // ✅ added
];

$page = $_GET['page'] ?? 'overview';
if (!in_array($page, $allowed_pages)) {
    $page = 'overview';
}

function isActive($key, $page) {
    return $key === $page ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-indigo-700 hover:text-white';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Employee Dashboard — GRMxpert</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .sidebar { background: linear-gradient(180deg,#0f172a,#111827); }
    .nav-icon { width: 18px; height: 18px; }
  </style>
</head>
<body class="antialiased">

  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 sidebar text-gray-300 hidden md:block">
      <div class="p-6">
        <a href="employee_dashboard.php" class="flex items-center gap-3 mb-8">
          <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M12 9v6" />
            </svg>
          </div>
          <div>
            <div class="text-white font-bold text-lg">GRMxpert</div>
            <div class="text-xs text-gray-400">Employee Portal</div>
          </div>
        </a>

        <!-- Menu -->
        <nav class="space-y-1">
          <a href="employee_dashboard.php?page=overview" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('overview',$page); ?>">
            <span class="nav-icon">🏠</span>
            <span class="text-sm">Overview</span>
          </a>

          <div class="mt-3">
            <div class="text-xs text-gray-400 uppercase px-3 mb-2">My Requests</div>
            <a href="employee_dashboard.php?page=files_upload" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('files_upload',$page); ?>">
              <span class="nav-icon">⬆️</span>
              <span class="text-sm">Upload Files</span>
            </a>
            <a href="employee_dashboard.php?page=leave_request" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('leave_request',$page); ?>">
              <span class="nav-icon">📝</span>
              <span class="text-sm">File Leave</span>
            </a>
          </div>

          <!-- ✅ Profile Section -->
          <div class="mt-3">
            <div class="text-xs text-gray-400 uppercase px-3 mb-2">Account</div>
            <a href="employee_dashboard.php?page=profile" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('profile',$page); ?>">
              <span class="nav-icon">👤</span>
              <span class="text-sm">Edit Profile</span>
            </a>
          </div>

          <div class="mt-6">
            <a href="logout.php" class="flex items-center gap-3 px-3 py-2 rounded text-red-400 hover:bg-red-600 hover:text-white">
              <span class="nav-icon">🚪</span>
              <span class="text-sm">Logout</span>
            </a>
          </div>
        </nav>
      </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 min-h-screen bg-gray-100">
      <!-- Topbar -->
      <header class="flex items-center justify-between px-6 py-4 bg-white shadow">
        <div class="flex items-center gap-4">
          <button id="toggleSidebar" class="md:hidden p-2 rounded bg-gray-100">☰</button>
          <h1 class="text-xl font-semibold">Employee Dashboard</h1>
        </div>

        <div class="flex items-center gap-4">

          <!-- ✅ Optional quick link -->
          <a href="employee_dashboard.php?page=profile"
             class="hidden sm:inline-flex px-3 py-2 rounded-lg border text-sm font-semibold hover:bg-gray-50">
            Edit Profile
          </a>

          <div class="text-sm text-gray-600">
            <?php echo htmlspecialchars($_SESSION['user']['fullname'] ?? 'Employee'); ?>
          </div>

          <img src="<?php echo htmlspecialchars($avatar_url); ?>"
               alt="avatar"
               class="w-9 h-9 rounded-full object-cover">
        </div>
      </header>

      <main class="p-6">
        <div class="max-w-7xl mx-auto">

          <!-- ✅ show success / error messages -->
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

          <?php include __DIR__ . "/employee_pages/{$page}.php"; ?>
        </div>
      </main>
    </div>
  </div>

  <script>
    document.getElementById('toggleSidebar').addEventListener('click', function(){
      const as = document.querySelector('aside');
      if (as.style.display === 'block') as.style.display = '';
      else as.style.display = 'block';
    });
  </script>
</body>
</html>
