<?php
include('db.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'Nicht autorisiert']);
    exit;
}

$cart = json_decode(file_get_contents('php://input'), true);
$totalCost = 0;

foreach ($cart as $item) {
    $totalCost += $item['preis'] * $item['quantity'];
}

$stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->bind_param('i', $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['balance'] < $totalCost) {
    echo json_encode(['error' => 'Nicht genug Guthaben']);
    exit;
}

// Guthaben aktualisieren
$updateBalance = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
$updateBalance->bind_param('di', $totalCost, $_SESSION['userId']);
$updateBalance->execute();

// Lagerbestand aktualisieren
$updateStock = $conn->prepare("UPDATE produkte SET anzahl = anzahl - ? WHERE produktid = ?");
foreach ($cart as $item) {
    $updateStock->bind_param('ii', $item['quantity'], $item['produktid']);
    $updateStock->execute();
}

// QR-Code erstellen
require 'phpqrcode/qrlib.php';  // Pfad zu deiner QR-Code Bibliothek anpassen

// QR-Code Inhalt vorbereiten
$qrContent = '';
foreach ($cart as $item) {
    $qrContent .=$item['produktid']. $item['quantity'] . "\n";
}

$qrCodeFile = 'qrcode_' . uniqid() . '.png';
QRcode::png($qrContent, $qrCodeFile, 'L', 4, 2);

echo json_encode([
    'message' => 'Bezahlung erfolgreich',
    'qrCode' => $qrCodeFile
]);

$conn->close();
?>
