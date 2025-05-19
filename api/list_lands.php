<?php
header('Content-Type: application/json');
require 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($id) {
  // Fetch single record by ID
  $stmt = $conn->prepare("SELECT * FROM lands WHERE id = ?");
  $stmt->bind_param("i", $id);
} else {
  // Fetch all records
  $stmt = $conn->prepare("SELECT * FROM lands ORDER BY id DESC");
}

$stmt->execute();
$result = $stmt->get_result();

$lands = [];

while ($row = $result->fetch_assoc()) {
  $lands[] = [
    'id' => $row['id'],
    'owner' => $row['owner'],
    'location' => $row['location'],
    'size' => $row['size'],
    'value' => $row['value']
  ];
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'lands' => $lands]);
?>
