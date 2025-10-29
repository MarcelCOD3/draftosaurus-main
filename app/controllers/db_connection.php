<?php
$servername = "database";
$username = "root";
$password = "root123"; //
$dbname = "draftoDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error de conexión a la base de datos: ' . $conn->connect_error]);
    exit();
}
?>
