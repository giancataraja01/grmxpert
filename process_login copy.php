<?php
// process_login.php

session_start();

// 1. Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php?error=Invalid request.");
    exit();
}

// 2. Get form data
$login_id   = trim($_POST['login_id'] ?? '');
$password   = trim($_POST['password'] ?? '');
$rememberMe = isset($_POST['remember_me']) ? 1 : 0;

// 3. Simple validation
if ($login_id === '' || $password === '') {
    header("Location: login.php?error=Please fill in all fields.");
    exit();
}

// 4. Database connection using PDO
$host = "localhost";
$dbname = "grmxpertdb";
$username = "student";   // change if needed
$dbpassword = "12345678";     // change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $dbpassword);
    // Set PDO error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header("Location: login.php?error=Database connection failed.");
    exit();
}

// 5. Check if user exists (by email OR username)
// Adjust the column names based on your users table schema.
// Example columns: id, full_name, email, username (optional), password, role
// If you don't have a username column, just check email and skip username.
$sql = "SELECT id, full_name, email, password, role 
        FROM users 
        WHERE email = :login_id /*OR username = :login_id*/
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':login_id', $login_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // No user found
    header("Location: login.php?error=Invalid email/username or password.");
    exit();
}

// 6. Verify password
if (!password_verify($password, $user['password'])) {
    header("Location: login.php?error=Invalid email/username or password.");
    exit();
}

// 7. If password is correct → start session
$_SESSION['user'] = [
    'id'        => $user['id'],
    'fullname'  => $user['full_name'],
    'email'     => $user['email'],
    'role'      => $user['role']
];

// 8. OPTIONAL: Remember Me (store a simple token in cookie)
// For real security, generate a unique token and store in DB.
// This is a BASIC example:
if ($rememberMe) {
    // Cookie expires in 30 days
    setcookie("remember_user", $user['id'], time() + (30*24*60*60), "/");
}

// 9. Role-based redirect
if ($user['role'] === 'admin') {
    header("Location: admin_dashboard.php");
} else if ($user['role'] === 'dept_head') {
    header("Location: dept_head_dashboard.php"); // create this page later
} else if ($user['role'] === 'employee') {
    header("Location: employee_dashboard.php"); // create this page later
} else {
    // Default fallback
    header("Location: login.php?error=Unknown role. Contact admin.");
}

exit();
