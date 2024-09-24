<?php
// Incluir el archivo de configuración
$config = include('config.php');

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener los datos del formulario
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$api_name = isset($_POST['api_name']) ? $conn->real_escape_string($_POST['api_name']) : '';
$api_url = isset($_POST['api_url']) ? $conn->real_escape_string($_POST['api_url']) : '';
$api_user = isset($_POST['api_user']) ? $conn->real_escape_string($_POST['api_user']) : '';
$api_password = isset($_POST['api_password']) ? $conn->real_escape_string($_POST['api_password']) : '';
$json_body = isset($_POST['json_body']) ? $conn->real_escape_string($_POST['json_body']) : '';
$execution_interval = isset($_POST['execution_interval']) ? (int)$_POST['execution_interval'] : 0;

// Verificar que se haya enviado un ID válido
if ($id > 0) {
    // Consulta para actualizar el log de API
    $sql = "UPDATE api_logs 
            SET api_name = '$api_name', api_url = '$api_url', api_user = '$api_user', 
                api_password = '$api_password', json_body = '$json_body', 
                execution_interval = $execution_interval 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redireccionar o mostrar un mensaje de éxito
        header('Location: success_page.php'); // Cambia esto a tu página de éxito
        exit;
    } else {
        echo "Error al actualizar el registro: " . $conn->error;
    }
} else {
    echo "ID de registro inválido.";
}

$conn->close();
?>
