<?php
// process_login.php
session_start();

// Toggle this to true ONLY while fixing login, then set back to false.
define('DEBUG_LOGIN', true);

function fail($msg) {
    header("Location: login.php?error=" . urlencode($msg));
    exit();
}

// 1. POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail("Invalid request.");
}

// 2. Inputs
$login_id   = trim($_POST['login_id'] ?? '');
$password   = $_POST['password'] ?? '';          // DO NOT trim passwords
$rememberMe = isset($_POST['remember_me']);

if ($login_id === '' || $password === '') {
    fail("Please fill in all fields.");
}

// 3. DB connect
$host = "localhost";
$dbname = "grmxpertdb";
$username = "student";
$dbpassword = "12345678";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $dbpassword,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    fail(DEBUG_LOGIN ? ("DB connection failed: " . $e->getMessage()) : "Database connection failed.");
}

// 4. Fetch user (email only; you don’t have username column yet)
try {
    $sql = "SELECT id, full_name, email, password, role
            FROM users
            WHERE LOWER(email) = LOWER(:login_id)
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':login_id' => $login_id]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    fail(DEBUG_LOGIN ? ("Query failed: " . $e->getMessage()) : "Database error.");
}

if (!$user) {
    fail(DEBUG_LOGIN ? "No user found for: {$login_id}" : "Invalid email or password.");
}

// 5. Verify bcrypt hash format quickly (helps diagnose)
$hash = $user['password'] ?? '';
if (DEBUG_LOGIN && !is_string($hash)) {
    fail("Password field is not a string in DB.");
}
if (DEBUG_LOGIN && !(str_starts_with($hash, '$2y$') || str_starts_with($hash, '$2a$') || str_starts_with($hash, '$2b$'))) {
    fail("Stored password is NOT bcrypt. It starts with: " . substr($hash, 0, 10));
}

// 6. Verify password
if (!password_verify($password, $hash)) {
    // Extra debug: tell if hash matches at all
    fail(DEBUG_LOGIN ? "Password verify failed for {$user['email']}" : "Invalid email or password.");
}

// 7. Success: session
session_regenerate_id(true);
$_SESSION['user'] = [
    'id'       => $user['id'],
    'fullname' => $user['full_name'],
    'email'    => $user['email'],
    'role'     => $user['role']
];

// 8. Remember me (basic)
if ($rememberMe) {
    setcookie("remember_user", (string)$user['id'], time() + (30*24*60*60), "/", "", false, true);
}

// 9. Role redirect (FIXED)
$role = $user['role'];

if ($role === 'admin') {
    header("Location: admin_dashboard.php");
} elseif ($role === 'dept_head') {
    header("Location: dept_head_dashboard.php");
} elseif ($role === 'employee') {
    header("Location: employee_dashboard.php");
} else {
    fail("Unknown role '{$role}'. Check ENUM values in users.role.");
}
exit();
