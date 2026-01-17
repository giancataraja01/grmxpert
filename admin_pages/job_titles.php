<?php
// admin_pages/job_titles.php

// Database connection
$host = 'localhost';
$db   = 'grmxpertdb';
$user = 'student';
$pass = '12345678';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$message = '';

// Handle Add Department
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add' && !empty($_POST['title'])) {
        $stmt = $pdo->prepare("INSERT INTO jobtitles (title) VALUES (:title)");
        if ($stmt->execute(['title' => $_POST['title']])) {
            $message = "Job Title added successfully!";
        } else {
            $message = "Failed to add department.";
        }
    }

    // Handle Delete Department
    if ($_POST['action'] === 'delete' && !empty($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM jobtitles WHERE id = :id");
        if ($stmt->execute(['id' => $_POST['id']])) {
            $message = "Department deleted successfully!";
        } else {
            $message = "Failed to delete department.";
        }
    }
}

// Fetch departments
$titles = $pdo->query("SELECT * FROM jobtitles ORDER BY title ASC")->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Jo Titles</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-3xl mx-auto">
  <div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-semibold mb-4">Job Titles</h2>

    <!-- Message -->
    <?php if ($message): ?>
      <div class="mb-4 p-3 rounded bg-green-100 text-green-800"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Add Department Form -->
    <form method="POST" class="mb-6 flex gap-2">
      <input type="text" name="title" placeholder="New Title" class="p-2 border rounded flex-1" required />
      <input type="hidden" name="action" value="add" />
      <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded">Add</button>
    </form>

    <!-- Departments List -->
    <ul class="space-y-2 text-sm">
      <?php if (!empty($titles)): ?>
        <?php foreach ($titles as $titl): ?>
          <li class="flex justify-between items-center">
            <div><?= htmlspecialchars($titl['title']) ?></div>
            <!-- Delete Form -->
            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this department?');">
              <input type="hidden" name="id" value="<?= $titl['id'] ?>" />
              <input type="hidden" name="action" value="delete" />
              <button type="submit" class="text-red-600">Delete</button>
            </form>
          </li>
        <?php endforeach; ?>
      <?php else: ?>
        <li>No Job title found.</li>
      <?php endif; ?>
    </ul>
  </div>
</div>

</body>
</html>
