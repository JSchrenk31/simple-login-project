<?php
include('db.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'Nicht autorisiert']);
    exit;
}

$stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->bind_param('i', $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo json_encode(['balance' => $user['balance']]);
$stmt->close();
$conn->close();
?>
