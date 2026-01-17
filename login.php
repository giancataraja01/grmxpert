<?php
// Optional: If redirected with an error via GET parameter, show it below
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login — GRMxpert</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .hero-bg {
      background: linear-gradient(90deg, rgba(79,70,229,0.92), rgba(139,92,246,0.9));
    }
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
        <a href="index.php#features" class="text-white hover:underline">Features</a>
        <a href="index.php#about" class="text-white hover:underline">About</a>
        <a href="register.php" class="ml-2 px-4 py-2 bg-white text-indigo-600 rounded-lg font-semibold shadow">
          Register
        </a>
      </div>

      <button id="btnMobile" class="md:hidden p-2 text-white">
        <svg id="iconOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
        </svg>
      </button>
    </nav>

    <div id="mobileMenu" class="hidden md:hidden bg-indigo-700/95">
      <div class="px-6 py-6 space-y-4">
        <a href="index.php#features" class="block text-white">Features</a>
        <a href="index.php#about" class="block text-white">About</a>
        <a href="register.php" class="block bg-white text-indigo-600 px-4 py-2 rounded">
          Register
        </a>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="hero-bg text-white pt-28 pb-12">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <h1 class="text-3xl md:text-4xl font-extrabold">Welcome Back</h1>
      <p class="mt-2 text-indigo-100/90">Log in to access your HRIS dashboard</p>
    </div>
  </section>

  <!-- LOGIN CARD -->
  <main class="max-w-md mx-auto px-6 -mt-12 mb-16">
    <div class="bg-white rounded-2xl shadow p-6 md:p-10">

      <!-- Show server-side messages -->
      <?php if (!empty($_GET['error'])): ?>
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
          <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($_GET['success'])): ?>
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded">
          <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
      <?php endif; ?>

      <form id="loginForm" method="POST" action="process_login.php" novalidate>
        
        <!-- Email or Username -->
        <div>
          <label for="login_id" class="block text-sm font-medium text-gray-700">
            Email or Username
          </label>
          <input id="login_id" name="login_id" type="text" class="mt-1 block w-full border rounded p-3" required>
          <p class="mt-1 text-xs text-red-600 hidden" id="err_login_id"></p>
        </div>

        <!-- Password -->
        <div class="mt-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <div class="relative mt-1">
            <input id="password" name="password" type="password" class="block w-full border rounded p-3 pr-12" required>
            <button type="button" id="togglePwd" class="absolute right-2 top-2 text-sm text-gray-600 px-2 py-1">Show</button>
          </div>
          <p class="mt-1 text-xs text-red-600 hidden" id="err_password"></p>
        </div>

        <!-- Remember Me + Forgot Password -->
        <div class="mt-4 flex items-center justify-between">
          <label class="inline-flex items-center text-sm text-gray-600">
            <input type="checkbox" name="remember_me" class="mr-2"> Remember me
          </label>
          <a href="#" class="text-sm text-indigo-600 hover:underline">Forgot Password?</a>
        </div>

        <!-- Submit -->
        <div class="mt-6">
          <button type="submit" id="submitBtn" class="w-full py-3 bg-indigo-600 text-white rounded-lg shadow font-semibold">
            Login
          </button>
        </div>

        <!-- Register link -->
        <div class="mt-4 text-center text-sm">
          Don't have an account?
          <a href="register.php" class="text-indigo-600 font-semibold">Create one</a>
        </div>

      </form>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-gray-900 text-gray-300 py-10">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-6">
      <div>
        <div class="font-bold text-white">GRMxpert</div>
        <div class="text-sm mt-2">Web-based HRIS for educational institutions.</div>
      </div>
      <div>
        <div class="font-semibold text-white">Quick Links</div>
        <ul class="mt-3 text-sm space-y-2">
          <li><a href="index.php#features" class="hover:underline">Features</a></li>
          <li><a href="register.php" class="hover:underline">Register</a></li>
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

    // Show/Hide password
    const togglePwd = document.getElementById('togglePwd');
    const pwd = document.getElementById('password');
    togglePwd.addEventListener('click', () => {
      if (pwd.type === 'password') {
        pwd.type = 'text';
        togglePwd.textContent = 'Hide';
      } else {
        pwd.type = 'password';
        togglePwd.textContent = 'Show';
      }
    });

    // Validation
    function showError(id, msg) {
      const el = document.getElementById(id);
      if (msg) {
        el.textContent = msg;
        el.classList.remove('hidden');
      } else {
        el.textContent = '';
        el.classList.add('hidden');
      }
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
      let valid = true;
      showError('err_login_id', '');
      showError('err_password', '');

      const loginId = document.getElementById('login_id').value.trim();
      const passwordVal = pwd.value;

      if (!loginId) {
        showError('err_login_id', 'Email or Username is required.');
        valid = false;
      }

      if (!passwordVal) {
        showError('err_password', 'Password is required.');
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
      }
    });
  </script>
</body>
</html>
