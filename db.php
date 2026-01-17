<?php
$host = "localhost";
$user = "student";
$pass = "12345678";
$dbname = "grmxpertdb"; // change to your DB name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
