<?php

function executeRequest($request, $db) {
    // Decodificar datos de la solicitud almacenada
    $url = $request['url'];
    $method = $request['method'];
    $headers = json_decode($request['headers'], true);
    $auth = json_decode($request['auth'], true);
    $body = json_decode($request['body'], true);

    // Inicializar cURL
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Configurar método (POST, GET, etc.)
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
    if (!empty($auth)) {
        if (isset($auth['type']) && $auth['type'] == 'basic' && isset($auth['basic'])) {
            $username = $auth['basic'][1]['value'] ?? ''; // Validar que exista el índice 1
            $password = $auth['basic'][0]['value'] ?? ''; // Validar que exista el índice 0
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        }
        // Se puede agregar autenticación de token si es necesario
    }

    // Configurar cuerpo de la solicitud si es un método POST o PUT
    if ($method == 'POST' || $method == 'PUT') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Obtener el código de estado HTTP

    // Guardar en la base de datos
    try {
        // Preparar la inserción del log de la solicitud
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

function executePostmanCollection($collectionId) {
    try {
        $db = new PDO('mysql:host=localhost;dbname=postman_db', 'root', 'admin');

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

function executeAllCollections() {
    try {
        $db = new PDO('mysql:host=localhost;dbname=postman_db', 'root', 'admin');

        // Obtener todas las colecciones
        $stmt = $db->query("SELECT id FROM postman_collections"); // Asegúrate de que este nombre de tabla sea correcto
        $collections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ejecutar cada colección
        foreach ($collections as $collection) {
            echo "Ejecutando colección con ID: " . $collection['id'] . "<br>";
            executePostmanCollection($collection['id']);
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Ejecutar todas las colecciones
executeAllCollections();
?>
