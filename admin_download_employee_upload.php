<?php
// admin_download_employee_upload.php
session_start();
require_once 'db.php';

if (empty($_SESSION['user']) || !in_array($_SESSION['user']['role'] ?? '', ['admin','dept_head'])) {
    http_response_code(403);
    exit("Access denied.");
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    exit("Invalid request.");
}

$stmt = $conn->prepare("
    SELECT original_name, file_path, mime_type
    FROM employee_uploads
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) {
    http_response_code(404);
    exit("File not found.");
}

$relativePath = $row['file_path'];      // e.g. uploads/employee_files/...
$fullPath = __DIR__ . '/' . $relativePath;

if (!file_exists($fullPath)) {
    http_response_code(404);
    exit("File missing on server.");
}

$mime = $row['mime_type'] ?: 'application/octet-stream';
$filename = $row['original_name'] ?: 'download';

header('Content-Description: File Transfer');
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Content-Length: ' . filesize($fullPath));
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

readfile($fullPath);
exit;
