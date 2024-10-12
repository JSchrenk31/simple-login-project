<?php
include('db.php');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['userId'] = $user['id'];
            header('Location: dashboard.html');
        } else {
            echo 'Falsches Passwort!';
        }
    } else {
        echo 'Benutzername nicht gefunden!';
    }

    $stmt->close();
    $conn->close();
}
?>
