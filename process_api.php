<?php
// Incluir el archivo de configuración
$config = include('config.php');

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $api_name = $_POST['api_name'];
    $api_url = $_POST['api_url'];
    $http_method = $_POST['http_method']; // Capturar el método HTTP
    $execution_interval = $_POST['execution_interval']; // Capturar el valor del intervalo
    $user = $_POST['api_user'] ?? '';
    $password = $_POST['api_password'] ?? '';

    // Capturar los parámetros de la API
    $api_params = $_POST['api_params'] ?? [];
    $params = [];
    foreach ($api_params as $param) {
        if (!empty($param['key']) && !empty($param['value'])) {
            $params[$param['key']] = $param['value'];
        }
    }

    // Capturar el cuerpo JSON si se proporciona
    $json_body = $_POST['json_body'] ?? '';

    // Si no se proporciona json_body, construirlo a partir de los parámetros
    if (empty($json_body)) {
        $params['nombreUsuario'] = $user; // Agregar el usuario a los parámetros
        $params['clave'] = $password; // Agregar la clave a los parámetros
        $json_body = json_encode($params);
    } else {
        // Validar el JSON ingresado si se proporciona
        $json_decoded = json_decode($json_body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "El JSON ingresado no es válido: " . json_last_error_msg();
            exit;
        }
    }

    // Configurar la solicitud cURL
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Configurar el método HTTP
    switch ($http_method) {
        case 'GET':
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            // Agregar parámetros a la URL para GET
            if (!empty($params)) {
                $api_url .= '?' . http_build_query($params);
                curl_setopt($ch, CURLOPT_URL, $api_url);
            }
            break;
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_body);
            break;
        case 'PUT':
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_body);
            break;
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    // Ejecutar la solicitud y capturar la respuesta
    $response = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Guardar en la base de datos
    if ($curl_error) {
        $response = $curl_error;
    }

    // Crear el request en formato JSON
    $request_data = [
        'url' => $api_url,
        'method' => $http_method,
        'body' => !empty($json_body) ? json_decode($json_body, true) : null,
        'params' => $params,
        'user' => $user,
    ];

    // Preparar el statement
    $stmt = $conn->prepare("INSERT INTO api_logs (api_name, api_url, http_method, execution_interval, status_code, response, request) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Variables para bind_param
    $status_code = $http_status;
    $response_body = $response;
    $request_json = json_encode($request_data);

    // Enlazar parámetros
    $stmt->bind_param("sssisss", $api_name, $api_url, $http_method, $execution_interval, $status_code, $response_body, $request_json);

    if ($stmt->execute()) {
        echo "Estado guardado correctamente en la base de datos.<br>";
    } else {
        echo "Error al guardar el estado: " . $conn->error;
    }

    $stmt->close();

    // Mostrar el resultado
    echo "<h2>Estado de la solicitud: $http_status</h2>";
    echo "<h3>Respuesta de la API:</h3>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}

$conn->close();
?>
