<?php
// admin_pages/user_profile.php
//session_start();
require_once 'db.php'; // your DB connection

// Make sure user is logged in
if (empty($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$errors = [];
$success = '';

// Fetch current user info
$stmt = $conn->prepare("SELECT full_name, email, avatar FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // optional

    if (!$full_name) $errors[] = "Full name is required.";
    if (!$email) $errors[] = "Email is required.";

    // Handle avatar upload
    $avatar_filename = $user['avatar']; // keep old avatar if no upload
    if (!empty($_FILES['avatar']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['avatar']['type'], $allowed_types)) {
            $errors[] = "Only JPG, PNG, and GIF files are allowed for avatar.";
        } else {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $avatar_filename = 'avatar_'.$user_id.'_'.time().'.'.$ext;
            $upload_dir = 'uploads/avatars/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            $upload_path = $upload_dir . $avatar_filename;
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_path)) {
                $errors[] = "Failed to upload avatar.";
            }
        }
    }

    if (empty($errors)) {
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, password=?, avatar=? WHERE id=?");
            $stmt->bind_param("ssssi", $full_name, $email, $hashedPassword, $avatar_filename, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, avatar=? WHERE id=?");
            $stmt->bind_param("sssi", $full_name, $email, $avatar_filename, $user_id);
        }

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            // Update session
            $_SESSION['user']['full_name'] = $full_name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['avatar'] = $avatar_filename;
            $user['avatar'] = $avatar_filename; // update current user info
        } else {
            $errors[] = "Failed to update profile. Try again.";
        }
    }
}
?>

<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">My Profile</h2>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="bg-red-100 text-red-800 p-2 rounded mb-4">
            <ul class="list-disc pl-5">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Avatar</label>
            <?php if ($user['avatar'] && file_exists('uploads/avatars/'.$user['avatar'])): ?>
                <img src="uploads/avatars/<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="w-20 h-20 rounded-full mb-2 object-cover">
            <?php else: ?>
                <img src="uploads/avatars/default.png" alt="Avatar" class="w-20 h-20 rounded-full mb-2 object-cover">
            <?php endif; ?>
            <input type="file" name="avatar" accept="image/*" class="block mt-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Full Name</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>"
                   class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"
                   class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Password <span class="text-gray-400">(leave blank to keep current)</span></label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save Changes</button>
    </form>
</div>
