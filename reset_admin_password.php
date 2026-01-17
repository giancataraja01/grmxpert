<?php
$host="localhost";
$dbname="grmxpertdb";
$username="student";
$dbpassword="12345678";

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $dbpassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$newPlain = "Employee@123";
$newHash  = password_hash($newPlain, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = :hash WHERE email = :email");
$stmt->execute([
  ':hash' => $newHash,
  ':email' => 'employee@grmxpert.com'
]);

echo "User password reset OK. Login with employee@grmxpert.com / Employee@123";
