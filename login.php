<?php
session_start();
require 'api/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: login.html");
  exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = trim($_POST['role'] ?? '');

if (!$username || !$password || !$role) {
  die("All fields are required. <a href='login.html'>Back</a>");
}

if (!in_array($role, ['admin', 'user'])) {
  die("Invalid role selected. <a href='login.html'>Back</a>");
}

// Query user with username and role
$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ? AND role = ?");
$stmt->bind_param("ss", $username, $role);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows !== 1) {
  $stmt->close();
  die("Invalid credentials or role. <a href='login.html'>Back</a>");
}

$stmt->bind_result($id, $hashed_password);
$stmt->fetch();

if (!password_verify($password, $hashed_password)) {
  $stmt->close();
  die("Invalid credentials or role. <a href='login.html'>Back</a>");
}

// Credentials correct
$stmt->close();
$conn->close();

// Store user information in session
$_SESSION['user_id'] = $id;
$_SESSION['username'] = $username;
$_SESSION['role'] = $role;

// Redirect to the appropriate dashboard based on user role
if ($role === 'admin') {
    header("Location: admin_dashboard.html");
} else {
    header("Location: worker_dashboard.html");
}
exit;
?>