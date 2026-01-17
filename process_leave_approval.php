<?php
// process_leave_approval.php
session_start();
require_once 'db.php';

function back($msg, $ok = false) {
    $key = $ok ? 'success' : 'error';
    header("Location: admin_dashboard.php?page=leave_requests&{$key}=" . urlencode($msg));
    exit;
}

// ✅ Only admin / dept_head can approve
if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role'] ?? '', ['admin', 'dept_head'])) {
    back("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    back("Invalid request.");
}

$leave_id = (int)($_POST['leave_id'] ?? 0);
$action   = $_POST['action'] ?? '';

if ($leave_id <= 0 || !in_array($action, ['approve', 'reject'])) {
    back("Invalid parameters.");
}

$status = ($action === 'approve') ? 'approved' : 'rejected';
$approver_id = (int)($_SESSION['user']['id'] ?? 0);

if ($approver_id <= 0) {
    back("Invalid session user.");
}

// ✅ Ensure leave request exists and is still pending
$stmt = $conn->prepare("SELECT status FROM leave_requests WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $leave_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) {
    back("Leave request not found.");
}

if ($row['status'] !== 'pending') {
    back("This request is already " . $row['status'] . ".");
}

// ✅ Update request: status + approved_by + approved_at
$stmt = $conn->prepare("
    UPDATE leave_requests
    SET status = ?, approved_by = ?, approved_at = NOW()
    WHERE id = ?
");
$stmt->bind_param("sii", $status, $approver_id, $leave_id);

if ($stmt->execute()) {
    $stmt->close();
    back("Leave request {$status}.", true);
} else {
    $err = $stmt->error;
    $stmt->close();
    back("Database error: " . $err);
}
