<?php
$host = 'db5016339465.hosting-data.io';
$user = 'dbu873017';
$password = 'Jonasipod1.';
$database = 'dbs13287960';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
}
?>
