<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
require 'api/db_connect.php';

// sanitize POST inputs
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$role = trim($_POST['role'] ?? '');

if (!$username || !$password || !$confirm_password || !$role) {
  die("All fields are required. <a href='register.html'>Back</a>");
}

if ($password !== $confirm_password) {
  die("Passwords do not match. <a href='register.html'>Back</a>");
}

if (!in_array($role, ['admin', 'user'])) {
  die("Invalid role selected. <a href='register.html'>Back</a>");
}

// Check if username already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
  $stmt->close();
  die("Username already taken. <a href='register.html'>Back</a>");
}
$stmt->close();

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashed_password, $role);
if ($stmt->execute()) {
  $stmt->close();
  $conn->close();
  echo "Registration successful. <a href='login.html'>Login here</a>.";
  exit;
} else {
  $stmt->close();
  $conn->close();
  die("Registration failed. Try again later. <a href='register.html'>Back</a>");
}
?>