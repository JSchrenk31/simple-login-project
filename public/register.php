<?php
// Verbindungsinformationen einbinden
include 'db.php';

// Fehleranzeige aktivieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // POST-Werte abfragen
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Überprüfen, ob beide Felder ausgefüllt wurden
    if (empty($username) || empty($password)) {
        echo "Nutzername und Passwort dürfen nicht leer sein.";
        exit();
    }

    // Passwort hashen
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Balance auf 50 setzen
    $balance = 50;

    // SQL Statement vorbereiten
    $stmt = $conn->prepare("INSERT INTO users (username, password, balance) VALUES (?, ?, ?)");

    // Überprüfen, ob das Statement vorbereitet werden konnte
    if ($stmt) {
        // Bind_param mit den korrekten Typen
        // 'ssi' -> 2 strings (username, password) und 1 integer (balance)
        $stmt->bind_param("ssi", $username, $hashed_password, $balance);

        // Statement ausführen
        if ($stmt->execute()) {
            // Erfolgreiche Registrierung -> Weiterleitung zur index.html
            header("Location: index.html");
            exit(); // Beenden, damit der Rest des Scripts nicht mehr ausgeführt wird
        } else {
            echo "Fehler bei der Registrierung: " . $stmt->error;
        }

        // Statement schließen
        $stmt->close();
    } else {
        echo "Fehler beim Vorbereiten des Statements: " . $conn->error;
    }

    // Verbindung schließen
    $conn->close();
}
?>
