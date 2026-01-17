<?php
// admin_dashboard.php

session_start();

require_once 'db.php'; // your DB connection

// ✅ Count pending leave requests (notification badge)
$pending_leave_count = 0;
$lr = $conn->query("SELECT COUNT(*) AS c FROM leave_requests WHERE status = 'pending'");
if ($lr) {
    $row = $lr->fetch_assoc();
    $pending_leave_count = (int)($row['c'] ?? 0);
}

// Fetch user's avatar from DB
$avatar_url = 'uploads/avatars/default.png'; // default avatar (fixed path)

if (!empty($_SESSION['user']['id'])) {
    $user_id = (int)$_SESSION['user']['id'];

    // Safely check if avatar column exists to avoid "Unknown column" crash
    $check = $conn->query("SHOW COLUMNS FROM users LIKE 'avatar'");
    if ($check && $check->num_rows > 0) {
        $stmt = $conn->prepare("SELECT avatar FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($avatar);
        $stmt->fetch();
        $stmt->close();

        // Check if avatar exists in uploads/avatars folder
        $avatar_path = __DIR__ . "/uploads/avatars/" . $avatar;
        if (!empty($avatar) && file_exists($avatar_path)) {
            $avatar_url = "uploads/avatars/" . $avatar;
        }
    }
}

// Whitelist of allowed page includes (filenames inside admin_pages/)
$allowed_pages = [
  'overview',
  'employees_list',
  'employees_add',
  'employees_documents',
  'departments',
  'job_titles',
  'files_upload',
  'files_manage',
  'leave_requests',
  'overtime_requests',
  'other_requests',
  'users_list',
  'roles_permissions',
  'reports_overview',
  'settings_company',
  'settings_policies',
  'user_profile',
];

// choose page from query string, default to overview
$page = $_GET['page'] ?? 'overview';
if (!in_array($page, $allowed_pages)) {
    $page = 'overview';
}

// helper to render active menu item
function isActive($key, $page) {
    return $key === $page ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:bg-indigo-700 hover:text-white';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Dashboard — GRMxpert</title>
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
        <a href="admin_dashboard.php" class="flex items-center gap-3 mb-8">
          <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M12 9v6" />
            </svg>
          </div>
          <div>
            <div class="text-white font-bold text-lg">GRMxpert</div>
            <div class="text-xs text-gray-400">Admin Console</div>
          </div>
        </a>

        <!-- Menu -->
        <nav class="space-y-1">
          <a href="admin_dashboard.php?page=overview" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('overview',$page); ?>">
            <span class="nav-icon">
              <svg class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3z"/>
              </svg>
            </span>
            <span class="text-sm">Dashboard</span>
          </a>

          <div class="mt-3">
            <div class="text-xs text-gray-400 uppercase px-3 mb-2">Employee Management</div>
            <a href="admin_dashboard.php?page=employees_list" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('employees_list',$page); ?>">
              <span class="nav-icon">👥</span>
              <span class="text-sm">View Employees</span>
            </a>
            <a href="admin_dashboard.php?page=employees_add" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('employees_add',$page); ?>">
              <span class="nav-icon">➕</span>
              <span class="text-sm">Add Employee</span>
            </a>
            <a href="admin_dashboard.php?page=employees_documents" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('employees_documents',$page); ?>">
              <span class="nav-icon">📂</span>
              <span class="text-sm">Documents</span>
            </a>
          </div>

          <div class="mt-3">
            <div class="text-xs text-gray-400 uppercase px-3 mb-2">Org Structure</div>
            <a href="admin_dashboard.php?page=departments" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('departments',$page); ?>">
              <span class="nav-icon">🏢</span>
              <span class="text-sm">Departments</span>
            </a>
            <a href="admin_dashboard.php?page=job_titles" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('job_titles',$page); ?>">
              <span class="nav-icon">🧾</span>
              <span class="text-sm">Job Titles</span>
            </a>
          </div>

          <!-- ✅ HIDDEN: Docs & Files section (but NOT removed) -->
          <div class="mt-3 hidden">
            <div class="text-xs text-gray-400 uppercase px-3 mb-2">Docs & Files</div>
            <a href="admin_dashboard.php?page=files_upload" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('files_upload',$page); ?>">
              <span class="nav-icon">⬆️</span>
              <span class="text-sm">Upload Files</span>
            </a>
            <a href="admin_dashboard.php?page=files_manage" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('files_manage',$page); ?>">
              <span class="nav-icon">🗂️</span>
              <span class="text-sm">Manage Files</span>
            </a>
          </div>

          <div class="mt-3">
            <div class="text-xs text-gray-400 uppercase px-3 mb-2">Approvals</div>

            <!-- ✅ Leave Requests with notification badge -->
            <a href="admin_dashboard.php?page=leave_requests"
               class="flex items-center justify-between gap-3 px-3 py-2 rounded <?php echo isActive('leave_requests',$page); ?>">
              <div class="flex items-center gap-3">
                <span class="nav-icon">📝</span>
                <span class="text-sm">Leave Requests</span>
              </div>

              <?php if ($pending_leave_count > 0): ?>
                <span class="ml-2 inline-flex items-center justify-center text-xs font-bold
                             bg-red-600 text-white rounded-full px-2 py-0.5">
                  <?php echo $pending_leave_count; ?>
                </span>
              <?php endif; ?>
            </a>

            <!-- ✅ HIDDEN: Overtime (but NOT removed) -->
            <a href="admin_dashboard.php?page=overtime_requests"
               class="hidden flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('overtime_requests',$page); ?>">
              <span class="nav-icon">⏱️</span>
              <span class="text-sm">Overtime</span>
            </a>

            <!-- ✅ HIDDEN: Other Requests (but NOT removed) -->
            <a href="admin_dashboard.php?page=other_requests"
               class="hidden flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('other_requests',$page); ?>">
              <span class="nav-icon">📨</span>
              <span class="text-sm">Other Requests</span>
            </a>
          </div>

          <!-- ✅ HIDDEN: Users & Roles section (but NOT removed) -->
          <div class="mt-3 hidden">
            <div class="text-xs text-gray-400 uppercase px-3 mb-2">Users & Roles</div>
            <a href="admin_dashboard.php?page=users_list" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('users_list',$page); ?>">
              <span class="nav-icon">👤</span>
              <span class="text-sm">Manage Users</span>
            </a>
            <a href="admin_dashboard.php?page=roles_permissions" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('roles_permissions',$page); ?>">
              <span class="nav-icon">🔐</span>
              <span class="text-sm">Roles & Permissions</span>
            </a>
          </div>

          <div class="mt-3">
            <div class="text-xs text-gray-400 uppercase px-3 mb-2">Profile</div>
            <a href="admin_dashboard.php?page=user_profile" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('user_profile',$page); ?>">
              <span class="nav-icon">🧑‍💼</span>
              <span class="text-sm">Manage Profile</span>
            </a>
          </div>

          <div class="mt-3">
            <div class="text-xs text-gray-400 uppercase px-3 mb-2">Analytics</div>
            <a href="admin_dashboard.php?page=reports_overview" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('reports_overview',$page); ?>">
              <span class="nav-icon">📊</span>
              <span class="text-sm">Reports</span>
            </a>
          </div>

          <!-- ✅ HIDDEN: Settings section (but NOT removed) -->
          <div class="mt-3 hidden">
            <div class="text-xs text-gray-400 uppercase px-3 mb-2">Settings</div>
            <a href="admin_dashboard.php?page=settings_company" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('settings_company',$page); ?>">
              <span class="nav-icon">⚙️</span>
              <span class="text-sm">Company Info</span>
            </a>
            <a href="admin_dashboard.php?page=settings_policies" class="flex items-center gap-3 px-3 py-2 rounded <?php echo isActive('settings_policies',$page); ?>">
              <span class="nav-icon">📜</span>
              <span class="text-sm">HR Policies</span>
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
          <h1 class="text-xl font-semibold">Admin Console</h1>
        </div>
        <div class="flex items-center gap-4">

          <!-- 🔔 Topbar Leave notification -->
          <a href="admin_dashboard.php?page=leave_requests" class="relative inline-flex items-center">
            <span class="text-xl">🔔</span>
            <?php if ($pending_leave_count > 0): ?>
              <span class="absolute -top-2 -right-2 inline-flex items-center justify-center
                           text-[10px] font-bold bg-red-600 text-white rounded-full min-w-[18px] h-[18px] px-1">
                <?php echo $pending_leave_count; ?>
              </span>
            <?php endif; ?>
          </a>

          <div class="text-sm text-gray-600">
            <?php
              echo isset($_SESSION['user']['fullname'])
                ? htmlspecialchars($_SESSION['user']['fullname'])
                : 'Guest';
            ?>
          </div>

          <img src="<?php echo htmlspecialchars($avatar_url); ?>"
               alt="avatar"
               class="w-9 h-9 rounded-full object-cover">
        </div>
      </header>

      <!-- Content area -->
      <main class="p-6">
        <div class="max-w-7xl mx-auto">
          <?php include __DIR__ . "/admin_pages/{$page}.php"; ?>
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
