<?php
// config.php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "panelapi";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}


date_default_timezone_set('America/Argentina/Buenos_Aires'); // Cambia a tu zona horaria

?>
