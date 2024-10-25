<?php

// Cargar las variables de entorno si aún no están cargadas
if (!getenv('APP_TIMEZONE')) {
    $dotenv = Dotenv\Dotenv::createImmutable('/var/www');
    $dotenv->load();
}

// Configurar la zona horaria
date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'UTC');

// Depuración: Imprimir la zona horaria actual
echo "Zona horaria actual: " . date_default_timezone_get() . PHP_EOL;


require '/var/www/vendor/autoload.php'; // Ruta correcta del autoload
$app = require_once '/var/www/bootstrap/app.php';

// Función para cargar las variables desde el archivo .env
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception(".env file not found");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Función para obtener la conexión a la base de datos
function getDatabaseConnection() {
    // Cargar las variables desde el archivo .env
    loadEnv('/var/www/.env'); // Ajustar la ruta si es necesario

    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $dbname = $_ENV['DB_DATABASE'] ?? 'apiloop';
    $username = $_ENV['DB_USERNAME'] ?? 'root';
    $password = $_ENV['DB_PASSWORD'] ?? 'admin';

    try {
        return new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    } catch (PDOException $e) {
        die('Error de conexión: ' . $e->getMessage());
    }
}

// Función para ejecutar una solicitud individual
function executeRequest($request, $db) {
    $url = $request['url'];
    $method = $request['method'];
    $headers = json_decode($request['headers'], true);
    $auth = json_decode($request['auth'], true);
    $body = json_decode($request['body'], true);

    // Inicializar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    // Configurar headers
    $formattedHeaders = [];
    if (is_array($headers)) {
        foreach ($headers as $header) {
            if (isset($header['key']) && isset($header['value'])) {
                $formattedHeaders[] = $header['key'] . ': ' . $header['value'];
            }
        }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $formattedHeaders);

    // Configurar autenticación si es necesario
    if (!empty($auth) && isset($auth['type']) && $auth['type'] == 'basic') {
        $username = $auth['basic'][0]['value'] ?? '';
        $password = $auth['basic'][1]['value'] ?? '';
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    }

    // Configurar cuerpo de la solicitud si es POST o PUT
    if ($method == 'POST' || $method == 'PUT') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Guardar en la base de datos
    try {
        $stmt = $db->prepare("
            INSERT INTO request_logs (request_id, status, response, execution_time)
            VALUES (:request_id, :status, :response, NOW())
        ");
        $stmt->execute([
            ':request_id' => $request['id'],
            ':status' => $http_code,
            ':response' => $response
        ]);
    } catch (PDOException $e) {
        echo "Error al guardar el log: " . $e->getMessage();
    }

    // Manejo de errores de cURL
    if (curl_errno($ch)) {
        echo 'Error en la solicitud: ' . curl_error($ch);
    } else {
        echo "Respuesta HTTP: $http_code <br>";
        echo "Respuesta del servidor: $response <br>";
    }

    curl_close($ch);
}

// Función para ejecutar una colección de Postman
function executePostmanCollection($collectionId, $db) {
    try {
        // Obtener todas las solicitudes de la colección
        $stmt = $db->prepare("SELECT * FROM postman_requests WHERE collection_id = ?");
        $stmt->execute([$collectionId]);
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ejecutar cada solicitud
        foreach ($requests as $request) {
            echo "Ejecutando solicitud: " . $request['request_name'] . "<br>";
            executeRequest($request, $db);
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Función para ejecutar todas las colecciones
function executeAllCollections() {
    try {
        $db = getDatabaseConnection();

        // Obtener todas las colecciones
        $stmt = $db->query("SELECT id FROM postman_collections");
        $collections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ejecutar cada colección
        foreach ($collections as $collection) {
            echo "Ejecutando colección con ID: " . $collection['id'] . "<br>";
            executePostmanCollection($collection['id'], $db);
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Ejecutar todas las colecciones
executeAllCollections();

// Registrar la ejecución en un archivo de log
file_put_contents('/var/www/cron/cron_execution_log.txt', 'Ejecutado en: ' . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);
