<?php
if (isset($_GET['id'])) {
    $db = new PDO('mysql:host=localhost;dbname=postman_db', 'root', 'admin');

    // Eliminar los registros relacionados en request_logs
    $stmt = $db->prepare("DELETE FROM request_logs WHERE request_id = ?");
    $stmt->execute([$_GET['id']]);

    // Ahora eliminar la solicitud de postman_requests
    $stmt = $db->prepare("DELETE FROM postman_requests WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    header('Location: editar_coleccion.php');
    exit;
}
?>
