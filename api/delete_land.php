<?php
header('Content-Type: application/json');
require 'db_connect.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'])) {
  echo json_encode(['success' => false, 'message' => 'Missing land ID']);
  exit;
}

$id = intval($data['id']);
if ($id <= 0) {
  echo json_encode(['success' => false, 'message' => 'Invalid land ID']);
  exit;
}

$stmt = $conn->prepare("DELETE FROM lands WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  echo json_encode(['success' => true, 'message' => 'Land record deleted successfully']);
} else {
  echo json_encode(['success' => false, 'message' => 'Failed to delete land record']);
}

$stmt->close();
$conn->close();
?>
