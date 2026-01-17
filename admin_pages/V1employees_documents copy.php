<?php
// admin_pages/employees_documents.php

$message = '';

// Database connection
$conn = new mysqli('localhost', 'student', '12345678', 'grmxpertdb');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee'] ?? '';
    $document_type = $_POST['document_type'] ?? '';

    if (!empty($employee_id) && !empty($document_type) && isset($_FILES['doc']) && $_FILES['doc']['error'] === UPLOAD_ERR_OK) {
        $stmt = $conn->prepare("SELECT employee_no, first_name, last_name FROM employees WHERE id = ?");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $employee = $result->fetch_assoc();
            $stmt->close();

            // Create employee folder
            $folder_name = $employee['employee_no'] . '_' . str_replace(' ', '_', $employee['first_name'] . '_' . $employee['last_name']);
            $upload_dir = __DIR__ . '/uploads/' . $folder_name;
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Rename file: document_type + employee_no + fullname + timestamp + extension
            $original_ext = pathinfo($_FILES['doc']['name'], PATHINFO_EXTENSION);
            $timestamp = date('Ymd_His');
            $filename = str_replace(' ', '_', $document_type) . '_' .
                        $employee['employee_no'] . '_' .
                        str_replace(' ', '_', $employee['first_name'] . '_' . $employee['last_name']) . '_' .
                        $timestamp . '.' . $original_ext;

            $target_file = $upload_dir . '/' . $filename;

            if (move_uploaded_file($_FILES['doc']['tmp_name'], $target_file)) {
                // Success message with employee full name
                $message = "File uploaded successfully for " . $employee['first_name'] . " " . $employee['last_name'] . ".";

                // Save record in documents table
                $stmt2 = $conn->prepare("
                    INSERT INTO documents (employee_id, document_type, file_name, file_path, uploaded_at)
                    VALUES (?, ?, ?, ?, NOW())
                ");
                $file_path = 'uploads/' . $folder_name . '/' . $filename;
                $stmt2->bind_param("isss", $employee_id, $document_type, $filename, $file_path);
                $stmt2->execute();
                $stmt2->close();

            } else {
                $message = "Failed to move uploaded file.";
            }

        } else {
            $message = "Employee not found.";
        }

    } else {
        $message = "Please select an employee, document type, and choose a file to upload.";
    }
}

// Fetch all employees for the dropdown
$employees_result = $conn->query("SELECT id, employee_no, first_name, last_name FROM employees ORDER BY employee_no ASC");
?>

<div class="bg-white rounded-lg shadow p-6">
  <h2 class="text-2xl font-semibold mb-4">Employee Documents</h2>
  <p class="text-sm text-gray-600 mb-4">Upload and manage employee documents (contracts, IDs, diplomas).</p>

  <?php if ($message): ?>
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="POST" action="" enctype="multipart/form-data">
    <div class="grid md:grid-cols-2 gap-4">
      <!-- Employee dropdown -->
      <select name="employee" class="block w-full p-3 border border-gray-300 rounded-lg bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-700" required>
        <option value="">Select Employee</option>
        <?php if ($employees_result->num_rows > 0): ?>
          <?php while($employee = $employees_result->fetch_assoc()): ?>
            <option value="<?= $employee['id'] ?>">
              <?= htmlspecialchars($employee['employee_no'] . ' - ' . $employee['first_name'] . ' ' . $employee['last_name']) ?>
            </option>
          <?php endwhile; ?>
        <?php else: ?>
          <option value="">No employees found</option>
        <?php endif; ?>
      </select>

      <!-- Document type dropdown -->
      <select name="document_type" class="block w-full p-3 border border-gray-300 rounded-lg bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-gray-700" required>
        <option value="">Select Document Type</option>
        <option value="Contract">Contract</option>
        <option value="ID">ID</option>
        <option value="Diploma">Diploma</option>
        <option value="Other">Other</option>
      </select>

      <!-- File input -->
      <input type="file" name="doc" class="block w-full p-3 border border-gray-300 rounded-lg text-gray-700" required />
    </div>

    <div class="mt-4">
      <button class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition duration-200">
        Upload
      </button>
    </div>
  </form>
</div>

<?php
$conn->close();
?>
