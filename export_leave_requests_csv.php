<?php
session_start();
require_once 'db.php';

if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role'] ?? '', ['admin','dept_head'])) {
    http_response_code(403);
    exit("Access denied.");
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=leave_requests_' . date('Ymd_His') . '.csv');

$out = fopen('php://output', 'w');
fputcsv($out, ['id','employee','email','department','date_from','date_to','leave_type','reason','status','approved_by','approved_at','created_at']);

$sql = "
  SELECT
    lr.id,
    u.full_name AS employee,
    u.email,
    u.department,
    lr.date_from,
    lr.date_to,
    lr.leave_type,
    lr.reason,
    lr.status,
    a.full_name AS approved_by,
    lr.approved_at,
    lr.created_at
  FROM leave_requests lr
  JOIN users u ON u.id = lr.user_id
  LEFT JOIN users a ON a.id = lr.approved_by
  ORDER BY lr.created_at DESC
";

$res = $conn->query($sql);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        fputcsv($out, [
            $row['id'],
            $row['employee'],
            $row['email'],
            $row['department'],
            $row['date_from'],
            $row['date_to'],
            $row['leave_type'],
            $row['reason'],
            $row['status'],
            $row['approved_by'],
            $row['approved_at'],
            $row['created_at']
        ]);
    }
}

fclose($out);
exit;
