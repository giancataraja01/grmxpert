<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>GRMxpert — Web-based HRIS for Educational Institutions</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* small custom styles */
    .hero-bg {
      background: linear-gradient(90deg, rgba(79,70,229,0.92), rgba(139,92,246,0.9));
    }
    .glass {
      background: rgba(255,255,255,0.6);
      backdrop-filter: blur(6px);
    }
  </style>
</head>
<body class="antialiased text-gray-800 bg-gray-50">

  <!-- NAV -->
  <header class="fixed w-full z-30">
    <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <a href="#" class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow">
          <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M12 9v6" /></svg>
        </div>
        <span class="font-bold text-lg text-white/90">GRMxpert</span>
      </a>

      <div class="hidden md:flex items-center gap-6">
        <a href="#about" class="text-white hover:underline">About</a>
        <a href="#features" class="text-white hover:underline">Features</a>
        <a href="#how" class="text-white hover:underline">How it Works</a>
        <a href="#benefits" class="text-white hover:underline">Benefits</a>
        <a href="#contact" class="text-white hover:underline">Contact</a>
        <a href="login.php" class="ml-2 px-4 py-2 bg-white text-indigo-600 rounded-lg font-semibold shadow">Sign in</a>
      </div>

      <!-- mobile burger -->
      <button id="btnMobile" class="md:hidden p-2 text-white">
        <svg id="iconOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
      </button>
    </nav>

    <!-- mobile menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-indigo-700/95">
      <div class="px-6 py-6 space-y-4">
        <a href="#about" class="block text-white">About</a>
        <a href="#features" class="block text-white">Features</a>
        <a href="#how" class="block text-white">How it Works</a>
        <a href="#benefits" class="block text-white">Benefits</a>
        <a href="login.php" class="block mt-2 bg-white text-indigo-600 px-4 py-2 rounded">Sign in</a>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="hero-bg text-white pt-28 pb-16">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-8 items-center">
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">GRMxpert — Streamlined HRIS for Educational Institutions</h1>
        <p class="mt-4 text-indigo-100/90 text-lg md:text-xl max-w-xl">
          A web-based Human Resource Information System built to automate document management, employee records, monitoring, and tracking — purposefully designed for universities and schools.
        </p>
        <div class="mt-6 flex gap-3">
          <a href="#how" class="px-5 py-3 bg-white text-indigo-700 rounded-full font-semibold shadow hover:opacity-95">See How it Works</a>
          <a id="demoBtn" class="px-5 py-3 border border-white/30 rounded-full hover:bg-white/10 cursor-pointer">Request Demo</a>
        </div>

        <div class="mt-8 grid grid-cols-3 gap-4 max-w-md">
          <div class="bg-white/10 p-3 rounded-lg text-center">
            <div class="text-2xl font-bold" data-target="120">0</div>
            <div class="text-sm text-indigo-100/80">Total Employees</div>
          </div>
          <div class="bg-white/10 p-3 rounded-lg text-center">
            <div class="text-2xl font-bold" data-target="6">0</div>
            <div class="text-sm text-indigo-100/80">Mandatory Docs</div>
          </div>
          <div class="bg-white/10 p-3 rounded-lg text-center">
            <div class="text-2xl font-bold" data-target="95">0</div>
            <div class="text-sm text-indigo-100/80">Uptime %</div>
          </div>
        </div>
      </div>

      <!-- hero right: mockup -->
      <div class="relative">
        <div class="rounded-2xl shadow-2xl overflow-hidden glass p-4">
          <img src="https://images.unsplash.com/photo-1556157382-97eda2d62296?q=80&w=1400&auto=format&fit=crop&ixlib=rb-4.0.3&s=1c3b8e2d5c8a3f88786d1b3a2f3f981d" alt="dashboard mock" class="w-full h-64 object-cover rounded-md">
          <div class="mt-3">
            <div class="flex items-center justify-between">
              <div>
                <div class="text-sm font-semibold">Dashboard</div>
                <div class="text-xs text-gray-600">Real-time HR metrics & documents</div>
              </div>
              <div class="text-xs text-gray-600">Updated: Jul 2025</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section id="about" class="py-16">
    <div class="max-w-6xl mx-auto px-6">
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <div>
          <h2 class="text-3xl font-bold">About GRMxpert</h2>
          <p class="mt-4 text-gray-700">
            GRMxpert is a web-based Human Resource Information System that centralizes HR records and automates routine HR tasks. It is designed for tertiary-level educational institutions to improve efficiency, accuracy, and compliance.
          </p>

          <ul class="mt-6 space-y-3">
            <li class="flex items-start gap-3">
              <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700">1</div>
              <div>
                <div class="font-semibold">Problem solved</div>
                <div class="text-sm text-gray-600">Eliminates fragmented manual systems—like spreadsheets and paper files—that cause delays and inaccuracies.</div>
              </div>
            </li>

            <li class="flex items-start gap-3">
              <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700">2</div>
              <div>
                <div class="font-semibold">Target users</div>
                <div class="text-sm text-gray-600">HR staff, department heads, administrators, and employees seeking self-service and automated workflows.</div>
              </div>
            </li>
          </ul>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
          <h3 class="font-semibold text-lg">Project Objectives (summary)</h3>
          <ol class="mt-3 text-sm space-y-2 text-gray-700">
            <li>1. Determine current HR practices and challenges with HRIS adoption.</li>
            <li>2. Enable user profiles for different end users.</li>
            <li>3. Define document upload mechanisms supporting multiple file types.</li>
            <li>4. Design dashboards for employee records and HR metrics.</li>
            <li>5. Specify algorithms for notifications and messaging.</li>
            <li>6. Secure copyright for the web-based system.</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURES -->
  <section id="features" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center">Key Features</h2>
      <p class="text-center text-gray-600 mt-2 max-w-2xl mx-auto">A modular HRIS tailored for schools — focusing on records, documents, dashboards, and communications.</p>

      <div class="mt-10 grid md:grid-cols-3 gap-6">
        <div class="p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-b from-white to-gray-50">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-indigo-50 rounded-lg">
              <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-3 2-5 3-5s3 2 3 5-1 5-3 5-3-2-3-5zM12 11c0 3-2 5-3 5S6 14 6 11s1-5 3-5 3 2 3 5z"/></svg>
            </div>
            <div>
              <div class="font-semibold">Centralized Employee Records</div>
              <div class="text-sm text-gray-600">Store complete profiles, job information, and employment history in one secure place.</div>
            </div>
          </div>
        </div>

        <div class="p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-b from-white to-gray-50">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-indigo-50 rounded-lg">
              <svg class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div>
              <div class="font-semibold">Document Management</div>
              <div class="text-sm text-gray-600">Secure uploads, versioning, and audit trails for contracts, IDs, and certificates.</div>
            </div>
          </div>
        </div>

        <div class="p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-b from-white to-gray-50">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-indigo-50 rounded-lg">
              <svg class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 014 0v6m-4 0h4"/></svg>
            </div>
            <div>
              <div class="font-semibold">Dashboards & Analytics</div>
              <div class="text-sm text-gray-600">Real-time KPIs, departmental headcount, pending approvals, and document completeness checks.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-8 grid md:grid-cols-2 gap-6">
        <div class="mt-6 p-6 bg-indigo-50 rounded-xl">
          <h4 class="font-semibold">Notifications & Messaging</h4>
          <p class="text-sm text-gray-700 mt-2">Automated alerts and internal messaging keep users informed about documents, approvals, and profile updates.</p>
        </div>

        <div class="mt-6 p-6 bg-indigo-50 rounded-xl">
          <h4 class="font-semibold">Security & Compliance</h4>
          <p class="text-sm text-gray-700 mt-2">Role-based access, audit logs, and data protection features help institutions meet policy and regulatory requirements.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section id="how" class="py-16">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center">How GRMxpert Works</h2>
      <p class="text-center text-gray-600 mt-2 max-w-2xl mx-auto">A simple flow to move from manual HR processes to automated operations.</p>

      <div class="mt-10 grid md:grid-cols-4 gap-6 text-center">
        <div class="p-6 bg-white rounded-xl shadow">
          <div class="text-2xl font-bold">1</div>
          <div class="mt-2 font-semibold">Create Account</div>
          <div class="text-sm text-gray-600 mt-1">Register HR admins and employees or import data via CSV.</div>
        </div>
        <div class="p-6 bg-white rounded-xl shadow">
          <div class="text-2xl font-bold">2</div>
          <div class="mt-2 font-semibold">Add Employees</div>
          <div class="text-sm text-gray-600 mt-1">Capture core employee data and job details.</div>
        </div>
        <div class="p-6 bg-white rounded-xl shadow">
          <div class="text-2xl font-bold">3</div>
          <div class="mt-2 font-semibold">Upload Documents</div>
          <div class="text-sm text-gray-600 mt-1">Secure uploads, mandatory document tracking, and audit history.</div>
        </div>
        <div class="p-6 bg-white rounded-xl shadow">
          <div class="text-2xl font-bold">4</div>
          <div class="mt-2 font-semibold">Monitor & Act</div>
          <div class="text-sm text-gray-600 mt-1">Use dashboards, notifications, and messaging to manage HR tasks quickly.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- BENEFITS -->
  <section id="benefits" class="py-16 bg-indigo-600 text-white">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center">Benefits for Stakeholders</h2>

      <div class="mt-8 grid md:grid-cols-3 gap-6">
        <div class="p-6 bg-white/10 rounded-xl">
          <div class="font-semibold">HR Departments</div>
          <p class="text-sm mt-2">Reduce manual work, speed up reporting, and improve record accuracy.</p>
        </div>

        <div class="p-6 bg-white/10 rounded-xl">
          <div class="font-semibold">Employees</div>
          <p class="text-sm mt-2">Gain transparency over leaves, documents, and profiles through self-service tools.</p>
        </div>

        <div class="p-6 bg-white/10 rounded-xl">
          <div class="font-semibold">Administrators</div>
          <p class="text-sm mt-2">Make data-driven decisions with centralized records that support audits and compliance.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-16">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <h3 class="text-2xl font-bold">Ready to modernize HR in your institution?</h3>
      <p class="text-gray-600 mt-2">Start with a demo or request a pilot deployment for your campus.</p>
      <div class="mt-6 flex justify-center gap-3">
        <a href="register.php" class="px-6 py-3 bg-indigo-600 text-white rounded-full font-semibold shadow">Get Started</a>
        <a id="contactBtn" class="px-6 py-3 border border-indigo-600 rounded-full text-indigo-600 font-semibold cursor-pointer">Contact Sales</a>
      </div>
    </div>
  </section>

  <!-- FOOTER & CONTACT -->
  <footer id="contact" class="bg-gray-900 text-gray-300 py-10">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-6">
      <div>
        <div class="font-bold text-white">GRMxpert</div>
        <div class="text-sm mt-2">Web-based HRIS for educational institutions — centralize HR, secure records, and enable data-driven HR decisions.</div>
      </div>

      <div>
        <div class="font-semibold text-white">Quick Links</div>
        <ul class="mt-3 text-sm space-y-2">
          <li><a href="#features" class="hover:underline">Features</a></li>
          <li><a href="#how" class="hover:underline">How it works</a></li>
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
        <div>© <span id="yr"></span> GRMxpert. All rights reserved.</div>
        <div>Content pulled from the project summary and organized for this landing page.</div>
      </div>
    </div>
  </footer>

  <!-- Demo modal (simple) -->
  <div id="demoModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 max-w-lg w-full">
      <h3 class="text-lg font-semibold">Request a Demo</h3>
      <p class="text-sm text-gray-600 mt-2">Send us a message and we'll arrange a demo for your institution.</p>
      <form class="mt-4 grid gap-3">
        <input type="text" placeholder="Your name" class="p-3 border rounded" />
        <input type="email" placeholder="Work email" class="p-3 border rounded" />
        <textarea placeholder="Message / preferred date" class="p-3 border rounded"></textarea>
        <div class="flex justify-end gap-2">
          <button type="button" id="demoClose" class="px-4 py-2 border rounded">Cancel</button>
          <button type="button" class="px-4 py-2 bg-indigo-600 text-white rounded">Send</button>
        </div>
      </form>
    </div>
  </div>

  <!-- SCRIPTS -->
  <script>
    // year
    document.getElementById('yr').textContent = new Date().getFullYear();

    // mobile toggle
    const btn = document.getElementById('btnMobile');
    const menu = document.getElementById('mobileMenu');
    btn.addEventListener('click', () => menu.classList.toggle('hidden'));

    // smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', function(e){
        // allow default when href is just "#"
        const href = this.getAttribute('href');
        if (href === '#') return;
        e.preventDefault();
        const target = document.querySelector(href);
        if(target) target.scrollIntoView({behavior:'smooth', block:'start'});
        // close mobile menu when navigating
        if(!menu.classList.contains('hidden')) menu.classList.add('hidden');
      });
    });

    // demo modal
    const demoBtn = document.getElementById('demoBtn');
    const demoModal = document.getElementById('demoModal');
    const demoClose = document.getElementById('demoClose');
    const contactBtn = document.getElementById('contactBtn');
    demoBtn.addEventListener('click', () => demoModal.classList.remove('hidden'));
    demoClose.addEventListener('click', () => demoModal.classList.add('hidden'));
    contactBtn.addEventListener('click', () => demoModal.classList.remove('hidden'));

    // animated counters
    document.querySelectorAll('[data-target]').forEach(nodes => {
      const el = nodes;
      const target = +el.getAttribute('data-target');
      let cur = 0;
      const step = Math.max(1, Math.floor(target / 80));
      const id = setInterval(() => {
        cur += step;
        el.textContent = cur;
        if (cur >= target) {
          el.textContent = target;
          clearInterval(id);
        }
      }, 12);
    });

    // close modal on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !demoModal.classList.contains('hidden')) {
        demoModal.classList.add('hidden');
      }
    });

    // click outside to close modal
    demoModal.addEventListener('click', (e) => {
      if (e.target === demoModal) demoModal.classList.add('hidden');
    });
  </script>
</body>
</html>
