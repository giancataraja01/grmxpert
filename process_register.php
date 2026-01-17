<?php
// Database configuration
$host = "localhost";
$dbname = "grmxpertdb";
$username = "student";      // change if needed
$password = "12345678";          // change if needed

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Collect form data
    $full_name      = trim($_POST['full_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $confirm_pass   = $_POST['confirm_password'] ?? '';
    $role           = $_POST['role'] ?? 'employee';
    $employee_id    = trim($_POST['employee_id'] ?? null);
    $department     = trim($_POST['department'] ?? null);
    $contact_no     = trim($_POST['contact_no'] ?? null);

    // SERVER-SIDE VALIDATION
    if ($full_name === '' || $email === '' || $password === '' || $confirm_pass === '') {
        header("Location: register.php?error=" . urlencode("Please fill in all required fields."));
        exit;
    }

    // Password match
    if ($password !== $confirm_pass) {
        header("Location: register.php?error=" . urlencode("Passwords do not match."));
        exit;
    }

    // Email format validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=" . urlencode("Invalid email address."));
        exit;
    }

    // Role-based required fields
    if ($role === "employee") {
        if ($employee_id === '' || $department === '') {
            header("Location: register.php?error=" . urlencode("Employee ID and Department are required for employees."));
            exit;
        }
    }

    if ($role === "dept_head") {
        if ($department === '') {
            header("Location: register.php?error=" . urlencode("Department is required for Department Head."));
            exit;
        }
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL INSERT
    $sql = "INSERT INTO users (full_name, email, password, role, employee_id, department, contact_no)
            VALUES (:full_name, :email, :password, :role, :employee_id, :department, :contact_no)";

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([
            ':full_name'   => $full_name,
            ':email'       => $email,
            ':password'    => $hashed_password,
            ':role'        => $role,
            ':employee_id' => $employee_id ?: null,
            ':department'  => $department ?: null,
            ':contact_no'  => $contact_no ?: null,
        ]);

        // ✅ SUCCESS - redirect to login or welcome page
        header("Location: login.php?success=" . urlencode("Account created successfully. Please log in."));
        exit;

    } catch (PDOException $e) {
        // Catch duplicate email error or other DB issues
        if ($e->getCode() == 23000) { // Integrity constraint (e.g. duplicate email)
            header("Location: register.php?error=" . urlencode("Email already exists. Please use another."));
        } else {
            header("Location: register.php?error=" . urlencode("Database error: " . $e->getMessage()));
        }
        exit;
    }

} else {
    // If accessed directly (not via POST), redirect to register page.
    header("Location: register.php");
    exit;
}
