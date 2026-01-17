<?php
// Optional old input recall
$old = $_POST ?? [];
function old($key, $default = '') {
    global $old;
    return isset($old[$key]) ? htmlspecialchars($old[$key]) : $default;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Register — GRMxpert</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .hero-bg {
      background: linear-gradient(90deg, rgba(79,70,229,0.92), rgba(139,92,246,0.9));
    }
    .glass { background: rgba(255,255,255,0.6); backdrop-filter: blur(6px); }
    .role-selected { box-shadow: 0 8px 20px rgba(99,102,241,0.18); transform: translateY(-4px); }
  </style>
</head>
<body class="antialiased text-gray-800 bg-gray-50">

  <!-- NAV -->
  <header class="fixed w-full z-30">
    <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <a href="index.php" class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow">
          <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M12 9v6" />
          </svg>
        </div>
        <span class="font-bold text-lg text-white/90">GRMxpert</span>
      </a>

      <div class="hidden md:flex items-center gap-6">
        <a href="index.php#about" class="text-white hover:underline">About</a>
        <a href="index.php#features" class="text-white hover:underline">Features</a>
        <a href="index.php#how" class="text-white hover:underline">How it Works</a>
        <a href="index.php#benefits" class="text-white hover:underline">Benefits</a>
        <a href="login.php" class="ml-2 px-4 py-2 bg-white text-indigo-600 rounded-lg font-semibold shadow">Sign in</a>
      </div>

      <button id="btnMobile" class="md:hidden p-2 text-white">
        <svg id="iconOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
        </svg>
      </button>
    </nav>

    <div id="mobileMenu" class="hidden md:hidden bg-indigo-700/95">
      <div class="px-6 py-6 space-y-4">
        <a href="index.php#about" class="block text-white">About</a>
        <a href="index.php#features" class="block text-white">Features</a>
        <a href="index.php#how" class="block text-white">How it Works</a>
        <a href="index.php#benefits" class="block text-white">Benefits</a>
        <a href="login.php" class="block mt-2 bg-white text-indigo-600 px-4 py-2 rounded">Sign in</a>
      </div>
    </div>
  </header>

  <!-- Page Hero -->
  <section class="hero-bg text-white pt-28 pb-12">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <h1 class="text-3xl md:text-4xl font-extrabold">Create your GRMxpert account</h1>
      <p class="mt-3 text-indigo-100/90">Select your role and complete the registration form.</p>
    </div>
  </section>

  <!-- Registration Card -->
  <main class="max-w-4xl mx-auto px-6 -mt-12 mb-16">
    <div class="bg-white rounded-2xl shadow p-6 md:p-10">
      <div class="grid md:grid-cols-2 gap-6">
        <!-- LEFT INFO -->
        <div class="hidden md:block">
          <h2 class="text-2xl font-bold">Welcome to GRMxpert</h2>
          <p class="mt-3 text-sm text-gray-600">
            Register an account and access powerful HR tools designed for educational institutions.
          </p>

          <div class="mt-6 space-y-3">
            <div class="flex items-start gap-3">
              <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">✓</div>
              <div>
                <div class="font-semibold">Secure Access</div>
                <div class="text-sm text-gray-500">Role-based access and data protection.</div>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">⚙</div>
              <div>
                <div class="font-semibold">Role-Specific Features</div>
                <div class="text-sm text-gray-500">Forms adapt based on your role.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT FORM -->
        <div>
          <form id="regForm" method="POST" action="process_register.php" novalidate>
            <!-- role selection -->
            <input type="hidden" name="role" id="roleInput" value="<?php echo old('role','employee'); ?>">

            <label class="block text-sm font-medium text-gray-700">Register as</label>
            <div class="mt-3 grid grid-cols-3 gap-3">
              <button type="button" data-role="employee" class="role-btn p-3 rounded-lg border bg-white flex flex-col items-center">
                <span class="font-semibold">Employee</span>
                <span class="text-xs text-gray-500">Self-service</span>
              </button>
              <button type="button" data-role="dept_head" class="role-btn p-3 rounded-lg border bg-white flex flex-col items-center">
                <span class="font-semibold">Department Head</span>
                <span class="text-xs text-gray-500">Approvals</span>
              </button>
              <button type="button" data-role="hr_admin" class="role-btn p-3 rounded-lg border bg-white flex flex-col items-center">
                <span class="font-semibold">HR / Admin</span>
                <span class="text-xs text-gray-500">Full access</span>
              </button>
            </div>

            <!-- FORM FIELDS -->
            <div class="mt-6 space-y-4">
              <!-- Full Name -->
              <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input id="full_name" name="full_name" value="<?php echo old('full_name'); ?>" type="text" class="mt-1 block w-full border rounded p-3" required>
                <p class="mt-1 text-xs text-red-600 hidden" id="err_full_name"></p>
              </div>

              <!-- Email -->
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" value="<?php echo old('email'); ?>" type="email" class="mt-1 block w-full border rounded p-3" required>
                <p class="mt-1 text-xs text-red-600 hidden" id="err_email"></p>
              </div>

              <!-- Employee ID -->
              <div id="field_employee_id" class="hidden">
                <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee ID</label>
                <input id="employee_id" name="employee_id" value="<?php echo old('employee_id'); ?>" type="text" class="mt-1 block w-full border rounded p-3">
                <p class="mt-1 text-xs text-red-600 hidden" id="err_employee_id"></p>
              </div>
              <!-- Department -->
              <div id="field_department" class="hidden">
                <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                <select id="department" name="department" class="mt-1 block w-full border rounded p-3">
                  <option value="">Select department</option>
                  <option value="Academic" <?php echo (old('department')=='Academic'?'selected':''); ?>>Academic</option>
                  <option value="Administration" <?php echo (old('department')=='Administration'?'selected':''); ?>>Administration</option>
                  <option value="Finance" <?php echo (old('department')=='Finance'?'selected':''); ?>>Finance</option>
                  <option value="IT" <?php echo (old('department')=='IT'?'selected':''); ?>>IT</option>
                  <option value="HR" <?php echo (old('department')=='HR'?'selected':''); ?>>HR</option>
                </select>
                <p class="mt-1 text-xs text-red-600 hidden" id="err_department"></p>
              </div>

              <!-- Contact Number -->
              <div id="field_contact" class="hidden">
                <label for="contact_no" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input id="contact_no" name="contact_no" value="<?php echo old('contact_no'); ?>" type="text" class="mt-1 block w-full border rounded p-3" placeholder="+63 9xx xxx xxxx">
                <p class="mt-1 text-xs text-red-600 hidden" id="err_contact_no"></p>
              </div>

              <!-- Password -->
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative mt-1">
                  <input id="password" name="password" type="password" class="block w-full border rounded p-3 pr-12" minlength="8" required>
                  <button type="button" id="togglePwd" class="absolute right-2 top-2 text-sm text-gray-600 px-2 py-1">Show</button>
                </div>
                <p class="mt-1 text-xs text-gray-500">Minimum 8 characters.</p>
                <p class="mt-1 text-xs text-red-600 hidden" id="err_password"></p>
              </div>

              <!-- Confirm Password -->
              <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="confirm_password" name="confirm_password" type="password" class="mt-1 block w-full border rounded p-3" required>
                <p class="mt-1 text-xs text-red-600 hidden" id="err_confirm_password"></p>
              </div>
            </div>

            <!-- Display server-side errors if redirected with GET parameter -->
            <?php if (!empty($_GET['error'])): ?>
              <div class="mt-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
                <?php echo htmlspecialchars($_GET['error']); ?>
              </div>
            <?php endif; ?>

            <div class="mt-6 flex items-center justify-between">
              <span class="text-sm text-gray-600">Already have an account? <a href="login.php" class="text-indigo-600 font-semibold">Sign in</a></span>
              <button id="submitBtn" type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold shadow">Create Account</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-gray-900 text-gray-300 py-10">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-6">
      <div>
        <div class="font-bold text-white">GRMxpert</div>
        <div class="text-sm mt-2">Web-based HRIS for educational institutions — centralize HR, secure records, and enable data-driven HR decisions.</div>
      </div>

      <div>
        <div class="font-semibold text-white">Quick Links</div>
        <ul class="mt-3 text-sm space-y-2">
          <li><a href="index.php#features" class="hover:underline">Features</a></li>
          <li><a href="index.php#how" class="hover:underline">How it works</a></li>
          <li><a href="login.php" class="hover:underline">Sign in</a></li>
        </ul>
      </div>

      <div>
        <div class="font-semibold text-white">Contact</div>
        <div class="text-sm mt-2">Email: hello@grmxpert.example</div>
        <div class="text-sm">Phone: +63 912 345 6789</div>
      </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 mt-8 border-t border-gray-800 pt-6 text-xs text-gray-500">
      <div class="flex justify-between items-center">
        <div>© <?php echo date('Y'); ?> GRMxpert. All rights reserved.</div>
        <div>Secure • Tailored • Reliable</div>
      </div>
    </div>
  </footer>

  <!-- SCRIPTS -->
  <script>
    // Mobile menu toggle
    const btnMobile = document.getElementById('btnMobile');
    const mobileMenu = document.getElementById('mobileMenu');
    if (btnMobile) {
      btnMobile.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
      });
    }

    // ROLE SELECTION LOGIC
    const roleButtons = document.querySelectorAll('.role-btn');
    const roleInput = document.getElementById('roleInput');
    const fieldEmployeeID = document.getElementById('field_employee_id');
    const fieldDepartment = document.getElementById('field_department');
    const fieldContact = document.getElementById('field_contact');

    function setRoleUI(role) {
      roleInput.value = role;

      roleButtons.forEach(btn => {
        btn.classList.remove('role-selected', 'border-indigo-600', 'bg-indigo-50');
        if (btn.dataset.role === role) {
          btn.classList.add('role-selected', 'border-indigo-600', 'bg-indigo-50');
        }
      });

      // Default hide all optional fields
      fieldEmployeeID.classList.add('hidden');
      fieldDepartment.classList.add('hidden');
      fieldContact.classList.add('hidden');

      // Reset required attributes
      document.getElementById('employee_id').required = false;
      document.getElementById('department').required = false;

      if (role === 'employee') {
        fieldEmployeeID.classList.remove('hidden');
        fieldDepartment.classList.remove('hidden');
        fieldContact.classList.remove('hidden');
        document.getElementById('employee_id').required = true;
        document.getElementById('department').required = true;
      }

      if (role === 'dept_head') {
        fieldDepartment.classList.remove('hidden');
        document.getElementById('department').required = true;
      }

      if (role === 'hr_admin') {
        // No required extra fields
      }
    }

    // Event listeners for role buttons
    roleButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        setRoleUI(btn.dataset.role);
      });
    });

    // Initialize default role
    setRoleUI(roleInput.value || 'employee');
    // Show/Hide password
    const togglePwd = document.getElementById('togglePwd');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    togglePwd.addEventListener('click', () => {
      const type = password.type === 'password' ? 'text' : 'password';
      password.type = type;
      confirmPassword.type = type;
      togglePwd.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    // Simple email validation
    function validEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Show error helper
    function showError(id, message) {
      const el = document.getElementById(id);
      if (message) {
        el.textContent = message;
        el.classList.remove('hidden');
      } else {
        el.textContent = '';
        el.classList.add('hidden');
      }
    }

    // FORM VALIDATION
    const form = document.getElementById('regForm');
    form.addEventListener('submit', function(e) {
      // Clear existing errors
      ['err_full_name','err_email','err_employee_id','err_department','err_password','err_confirm_password','err_contact_no']
        .forEach(id => showError(id, ''));

      let valid = true;
      const fullName = document.getElementById('full_name').value.trim();
      const emailVal = document.getElementById('email').value.trim();
      const passwordVal = password.value;
      const confirmVal = confirmPassword.value;
      const role = roleInput.value;

      if (!fullName) {
        showError('err_full_name', 'Full name is required.');
        valid = false;
      }

      if (!emailVal) {
        showError('err_email', 'Email is required.');
        valid = false;
      } else if (!validEmail(emailVal)) {
        showError('err_email', 'Invalid email format.');
        valid = false;
      }

      if (role === 'employee') {
        const empId = document.getElementById('employee_id').value.trim();
        const dept = document.getElementById('department').value;
        if (!empId) {
          showError('err_employee_id', 'Employee ID is required for employees.');
          valid = false;
        }
        if (!dept) {
          showError('err_department', 'Department is required.');
          valid = false;
        }
      }

      if (role === 'dept_head') {
        const dept = document.getElementById('department').value;
        if (!dept) {
          showError('err_department', 'Department is required for Department Head.');
          valid = false;
        }
      }

      if (!passwordVal || passwordVal.length < 8) {
        showError('err_password', 'Password must be at least 8 characters.');
        valid = false;
      }

      if (passwordVal !== confirmVal) {
        showError('err_confirm_password', 'Passwords do not match.');
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
        const firstError = document.querySelector('.text-red-600:not(.hidden)');
        if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      } else {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
      }
    });

    // Live password match check
    confirmPassword.addEventListener('input', () => {
      if (password.value !== confirmPassword.value) {
        showError('err_confirm_password', 'Passwords do not match.');
      } else {
        showError('err_confirm_password', '');
      }
    });
  </script>
</body>
</html>
