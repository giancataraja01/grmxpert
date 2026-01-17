<?php
// admin_pages/employees_list.php

// Database connection
$conn = new mysqli('localhost', 'student', '12345678', 'grmxpertdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all employees
$result = $conn->query("SELECT * FROM employees ORDER BY id ASC");

// Capture message from URL
$message = $_GET['message'] ?? '';
?>

<div class="bg-white rounded-lg shadow p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-semibold">Employees</h2>
    <a href="admin_dashboard.php?page=employees_add" class="px-3 py-2 bg-indigo-600 text-white rounded">Add Employee</a>
  </div>

  <?php if ($message): ?>
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-left text-gray-600">
        <tr>
          <th class="p-2">Employee ID</th>
          <th class="p-2">Name</th>
          <th class="p-2">Department</th>
          <th class="p-2">Position</th>
          <th class="p-2">Hire Date</th>
          <th class="p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr class="border-t">
              <td class="p-2"><?= htmlspecialchars($row['employee_no']) ?></td>
              <td class="p-2"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
              <td class="p-2"><?= htmlspecialchars($row['department']) ?></td>
              <td class="p-2"><?= htmlspecialchars($row['position']) ?></td>
              <td class="p-2"><?= htmlspecialchars($row['hire_date']) ?></td>
              <td class="p-2">
                <a class="text-red-600" href="admin_pages/remove_employee.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to remove this employee?')">Remove</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="p-2 text-center text-gray-500">No employees found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$conn->close();
?>
