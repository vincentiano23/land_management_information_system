<?php
header('Content-Type: application/json');
require 'db_connect.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['owner'], $data['location'], $data['size'], $data['value'])) {
  echo json_encode(['success' => false, 'message' => 'Missing fields']);
  exit;
}

$owner = trim($data['owner']);
$location = trim($data['location']);
$size = floatval($data['size']);
$value = floatval($data['value']);

if ($owner === '' || $location === '' || $size <= 0 || $value < 0) {
  echo json_encode(['success' => false, 'message' => 'Invalid input values']);
  exit;
}

$stmt = $conn->prepare("INSERT INTO lands (owner, location, size, value) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssdd", $owner, $location, $size, $value);

if ($stmt->execute()) {
  echo json_encode(['success' => true, 'message' => 'Land record added successfully']);
} else {
  echo json_encode(['success' => false, 'message' => 'Failed to add land record']);
}

$stmt->close();
$conn->close();
?>
