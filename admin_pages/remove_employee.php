<?php
// admin_pages/remove_employee.php

// Check if 'id' is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request.");
}

$employee_id = $_GET['id'];

// Database connection
$conn = new mysqli('localhost', 'student', '12345678', 'grmxpertdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare delete statement
$stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
$stmt->bind_param("i", $employee_id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    // Redirect back to previous page
    $redirect_url = $_SERVER['HTTP_REFERER'] ?? 'admin_dashboard.php?page=employees_list';
    // Append success message
    $redirect_url .= (strpos($redirect_url, '?') === false ? '?' : '&') . 'message=' . urlencode('Employee removed successfully');
    header("Location: $redirect_url");
    exit;
} else {
    $error = $stmt->error;
    $stmt->close();
    $conn->close();
    die("Error deleting employee: " . $error);
}
?>
