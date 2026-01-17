<?php
// process_leave_request.php
session_start();
require_once 'db.php';

function back($msg, $ok = false) {
  $key = $ok ? 'success' : 'error';
  header("Location: employee_dashboard.php?page=leave_request&{$key}=" . urlencode($msg));
  exit;
}

// Access control
if (empty($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'employee') {
  back("Access denied.");
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  back("Invalid request.");
}

$user_id = (int)($_SESSION['user']['id'] ?? 0);
if ($user_id <= 0) back("Invalid session user.");

// Inputs
$date_from = $_POST['date_from'] ?? '';
$date_to   = $_POST['date_to'] ?? '';
$leave_type = $_POST['leave_type'] ?? '';
$reason    = trim($_POST['reason'] ?? '');

if ($date_from === '' || $date_to === '' || $leave_type === '' || $reason === '') {
  back("Please complete all fields.");
}

// Validate dates
$df = DateTime::createFromFormat('Y-m-d', $date_from);
$dt = DateTime::createFromFormat('Y-m-d', $date_to);

if (!$df || !$dt) {
  back("Invalid date format.");
}
if ($dt < $df) {
  back("End date must be on/after start date.");
}

// Validate leave_type
$allowedTypes = ['Vacation','Sick','Emergency','Other'];
if (!in_array($leave_type, $allowedTypes)) {
  back("Invalid leave type.");
}

// Insert
$stmt = $conn->prepare("INSERT INTO leave_requests (user_id, date_from, date_to, leave_type, reason)
                        VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $user_id, $date_from, $date_to, $leave_type, $reason);

if ($stmt->execute()) {
  $stmt->close();
  back("Leave request submitted!", true);
} else {
  $err = $stmt->error;
  $stmt->close();
  back("Database error: " . $err);
}
