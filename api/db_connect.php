<?php
// db_connect.php - MySQL connection for XAMPP setup

$servername = "localhost";
$username = "root";
$password = ""; // default for XAMPP is usually empty password
$dbname = "land_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
  exit();
}

// Set charset to UTF-8 for proper encoding
$conn->set_charset("utf8");
?>
