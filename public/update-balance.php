<?php
include('db.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'Nicht autorisiert']);
    exit;
}

// Raw Input holen und in ein Array umwandeln
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['amount'])) {
    echo json_encode(['error' => 'Fehlende Parameter']);
    exit;
}

$amount = (float) $input['amount'];  // Betrag aus den POST-Daten holen und in Float umwandeln
$userId = $_SESSION['userId'];       // ID des aktuellen Benutzers

// Bereite das SQL-Statement vor
$stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
$stmt->bind_param('di', $amount, $userId);  // Parameter binden

if ($stmt->execute()) {
    echo json_encode(['message' => 'Guthaben erfolgreich aktualisiert']);
} else {
    echo json_encode(['error' => 'Fehler beim Aktualisieren des Guthabens: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
?>
