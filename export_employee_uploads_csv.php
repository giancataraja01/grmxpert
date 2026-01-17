<?php
session_start();
require_once 'db.php';

if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role'] ?? '', ['admin','dept_head'])) {
    http_response_code(403);
    exit("Access denied.");
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=employee_uploads_' . date('Ymd_His') . '.csv');

$out = fopen('php://output', 'w');
fputcsv($out, ['id','employee','employee_id','department','original_name','stored_name','file_path','mime_type','file_size','description','status','created_at']);

$sql = "
  SELECT
    eu.id,
    u.full_name AS employee,
    u.employee_id,
    u.department,
    eu.original_name,
    eu.stored_name,
    eu.file_path,
    eu.mime_type,
    eu.file_size,
    eu.description,
    eu.status,
    eu.created_at
  FROM employee_uploads eu
  JOIN users u ON u.id = eu.user_id
  ORDER BY eu.created_at DESC
";

$res = $conn->query($sql);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        fputcsv($out, [
            $row['id'],
            $row['employee'],
            $row['employee_id'],
            $row['department'],
            $row['original_name'],
            $row['stored_name'],
            $row['file_path'],
            $row['mime_type'],
            $row['file_size'],
            $row['description'],
            $row['status'],
            $row['created_at']
        ]);
    }
}

fclose($out);
exit;
