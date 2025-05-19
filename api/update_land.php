<?php
header('Content-Type: application/json');
require 'db_connect.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'], $data['owner'], $data['location'], $data['size'], $data['value'])) {
  echo json_encode(['success' => false, 'message' => 'Missing fields']);
  exit;
}

$id = intval($data['id']);
$owner = trim($data['owner']);
$location = trim($data['location']);
$size = floatval($data['size']);
$value = floatval($data['value']);

if ($id <= 0 || $owner === '' || $location === '' || $size <= 0 || $value < 0) {
  echo json_encode(['success' => false, 'message' => 'Invalid input values']);
  exit;
}

$stmt = $conn->prepare("UPDATE lands SET owner = ?, location = ?, size = ?, value = ? WHERE id = ?");
$stmt->bind_param("ssd di", $owner, $location, $size, $value, $id);

if ($stmt->execute()) {
  echo json_encode(['success' => true, 'message' => 'Land record updated successfully']);
} else {
  echo json_encode(['success' => false, 'message' => 'Failed to update land record']);
}

$stmt->close();
$conn->close();
?>
