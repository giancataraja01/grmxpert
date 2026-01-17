<?php
// process_employee_upload.php
session_start();
require_once 'db.php';

function back($msg, $ok = false) {
  $key = $ok ? 'success' : 'error';
  header("Location: employee_dashboard.php?page=files_upload&{$key}=" . urlencode($msg));
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

$description = trim($_POST['description'] ?? '');

// Validate upload
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
  back("Please select a file to upload.");
}

$file = $_FILES['file'];

// Limits (adjust as needed)
$maxBytes = 10 * 1024 * 1024; // 10MB
if ($file['size'] > $maxBytes) {
  back("File too large. Max is 10MB.");
}

// Allowed types (adjust as you want)
$allowedExt = ['pdf','doc','docx','jpg','jpeg','png'];
$allowedMime = [
  'application/pdf',
  'application/msword',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
  'image/jpeg',
  'image/png'
];

// Get extension safely
$originalName = $file['name'];
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt)) {
  back("Invalid file type. Allowed: " . implode(", ", $allowedExt));
}

// MIME check using finfo (more reliable)
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']) ?: null;

if ($mime && !in_array($mime, $allowedMime)) {
  back("Invalid file content type.");
}

// Prepare destination
$uploadDir = __DIR__ . "/uploads/employee_files";
if (!is_dir($uploadDir)) {
  // Try create folder automatically
  if (!mkdir($uploadDir, 0755, true)) {
    back("Upload folder missing: uploads/employee_files (create it and try again).");
  }
}

// Stored filename (avoid collisions)
$storedName = "u{$user_id}_" . date('Ymd_His') . "_" . bin2hex(random_bytes(8)) . "." . $ext;
$destPath = $uploadDir . "/" . $storedName;

// Move file
if (!move_uploaded_file($file['tmp_name'], $destPath)) {
  back("Upload failed. Please try again.");
}

// Save record to DB
$stmt = $conn->prepare("INSERT INTO employee_uploads
  (user_id, original_name, stored_name, file_path, mime_type, file_size, description)
  VALUES (?, ?, ?, ?, ?, ?, ?)
");

$relativePath = "uploads/employee_files/" . $storedName;
$fileSize = (int)$file['size'];

$stmt->bind_param(
  "issssis",
  $user_id,
  $originalName,
  $storedName,
  $relativePath,
  $mime,
  $fileSize,
  $description
);

if ($stmt->execute()) {
  $stmt->close();
  back("File uploaded successfully!", true);
} else {
  // Cleanup file if DB insert fails
  @unlink($destPath);
  $err = $stmt->error;
  $stmt->close();
  back("Database error: " . $err);
}
