<?php
include('db.php');

$result = $conn->query("SELECT * FROM produkte");
$products = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($products);
$conn->close();
?>
