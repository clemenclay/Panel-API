<?php 
// Incluir el archivo de configuración
$config = include('config.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel API - Estado del Cron</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

 <?php
    // Incluir el archivo de menu
    include('menu.php');
 ?>

<div class="container">
    <h2 class="mb-4">Configurar Nueva API</h2>

    <form method="POST" action="process_api.php">
        <div class="mb-3">
            <label for="api_name" class="form-label">Nombre de API:</label>
            <input type="text" class="form-control" id="api_name" name="api_name" required>
        </div>

        <div class="mb-3">
            <label for="api_url" class="form-label">URL de API:</label>
            <input type="text" class="form-control" id="api_url" name="api_url" required>
        </div>

        <div class="mb-3">
            <label for="http_method" class="form-label">Método HTTP:</label>
            <select class="form-select" id="http_method" name="http_method" required>
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="PUT">PUT</option>
                <option value="DELETE">DELETE</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="execution_interval" class="form-label">Intervalo de Ejecución (en minutos):</label>
            <input type="number" class="form-control" id="execution_interval" name="execution_interval" min="1" required>
        </div>

        <div class="mb-3">
            <label for="api_user" class="form-label">Usuario (opcional):</label>
            <input type="text" class="form-control" id="api_user" name="api_user">
        </div>

        <div class="mb-3">
            <label for="api_password" class="form-label">Contraseña (opcional):</label>
            <input type="password" class="form-control" id="api_password" name="api_password">
        </div>


        <div class="mb-3">
            <label for="api_params" class="form-label">Parámetros (opcional):</label>
            <div id="params-container">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="api_params[0][key]" placeholder="Key">
                    <input type="text" class="form-control" name="api_params[0][value]" placeholder="Value">
                    <button class="btn btn-danger" type="button" onclick="removeParam(this)">X</button>
                </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="addParam()">Agregar Parámetro</button>
        </div>


        

        <div class="mb-3">
            <label for="json_body" class="form-label">JSON para enviar:</label>
            <textarea class="form-control" id="json_body" name="json_body" rows="5"></textarea>
        </div>

        <div class="mb-3">
            <label for="api_headers" class="form-label">Cabeceras adicionales (opcional, formato JSON):</label>
            <textarea class="form-control" id="api_headers" name="api_headers" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enviar JSON</button>
    </form>
</div>

<script>
function addParam() {
    const container = document.getElementById('params-container');
    const index = container.children.length;
    const newParam = `
        <div class="input-group mb-2">
            <input type="text" class="form-control" name="api_params[${index}][key]" placeholder="Key">
            <input type="text" class="form-control" name="api_params[${index}][value]" placeholder="Value">
            <button class="btn btn-danger" type="button" onclick="removeParam(this)">X</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newParam);
}

function removeParam(button) {
    button.parentElement.remove();
}
</script>


<?php
// Incluir el archivo de pie
$config = include('footer.php');
?>

</body>
</html>
