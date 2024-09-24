<?php

// Incluir el archivo de configuración
$config = include('config.php');

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID de la API desde el parámetro de la URL
$id_api = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Consulta para obtener los datos de api_logs basados en el id_api
$sql = "SELECT * FROM api_logs WHERE id = $id_api";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $api_log = $result->fetch_assoc();
} else {
    echo "No se encontraron registros para esta API.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar API Log - <?php echo htmlspecialchars($api_log['api_name']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<?php
// Incluir el archivo de menu
$config = include('menu.php');
?>



<div class="container">
    <h2 class="mb-4">Editar Log para <?php echo htmlspecialchars($api_log['api_name']); ?></h2>

    <form method="POST" action="update_api_log.php">
        <input type="hidden" name="id" value="<?php echo $api_log['id']; ?>">

        <div class="mb-3">
            <label for="estado" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="api_name" name="api_name" value="<?php echo htmlspecialchars($api_log['api_name']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="api_url" class="form-label">api_url:</label>
            <input type="text" class="form-control" id="api_url" name="api_url" value="<?php echo htmlspecialchars($api_log['api_url']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="api_user" class="form-label">api_user:</label>
            <input type="text" class="form-control" id="api_user" name="api_user" value="<?php echo htmlspecialchars($api_log['api_user']); ?>" >
        </div>

        <div class="mb-3">
            <label for="api_password" class="form-label">api_password:</label>
            <input type="text" class="form-control" id="api_password" name="api_password" value="<?php echo htmlspecialchars($api_log['api_password']); ?>" >
        </div>

        <div class="mb-3">
            <label for="json_body" class="form-label">json_body:</label>
            <input type="text" class="form-control" id="json_body" name="json_body" value="<?php echo htmlspecialchars($api_log['json_body']); ?>" >
        </div>

        <div class="mb-3">
            <label for="execution_interval" class="form-label">execution_interval:</label>
            <input type="text" class="form-control" id="execution_interval" name="execution_interval"
             value="<?php echo htmlspecialchars($api_log['execution_interval'] ?? ''); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

</body>
</html>
