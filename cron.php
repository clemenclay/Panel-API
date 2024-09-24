<?php
require 'config.php'; // Cargar la configuración de la base de datos

// Función para escribir en el archivo de log
function writeToLog($message) {
    $logFile = 'cron.log'; // El archivo de log
    $timestamp = date("Y-m-d H:i:s"); // Fecha y hora actuales
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND); // Añadir al log
}

// Función para registrar el estado del cron
function updateCronStatus($status) {
    $statusFile = 'cron_status.txt'; // Archivo para el estado del cron
    $timestamp = date("Y-m-d H:i:s");
    $statusMessage = "[$timestamp] Estado del Cron: $status" . PHP_EOL;
    file_put_contents($statusFile, $statusMessage); // Guardar el estado
}

// Desactivar el límite de tiempo de ejecución del script
set_time_limit(0);

// Registrar en el log que el cron ha iniciado
writeToLog('El cron se ha iniciado.');
updateCronStatus('Corriendo');

function consultarAPI($apiData) {
    global $conn; 

    $url = $apiData['api_url'];
    $usuario = $apiData['api_user'];
    $contraseña = $apiData['api_password'];
    $api_name = $apiData['api_name'];
    $id_api = $apiData['id'];

    // Configurar CURL para hacer la solicitud
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    if (!empty($usuario) && !empty($contraseña)) {
        curl_setopt($ch, CURLOPT_USERPWD, "$usuario:$contraseña");
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Manejo de errores en la consulta CURL
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        writeToLog("Error en la consulta CURL para $api_name: $error_msg");
        guardarEstadoEnBD($id_api, $api_name, "ERROR", "Error en CURL: $error_msg");
    } else {
        // Validar el código HTTP
        if ($http_code >= 200 && $http_code < 300) {
            writeToLog("Respuesta exitosa de la API $api_name.");
            guardarEstadoEnBD($id_api, $api_name, "OK", $response);
        } else {
            writeToLog("Error en la API $api_name. Código HTTP: $http_code");
            guardarEstadoEnBD($id_api, $api_name, "ERROR", "HTTP Code: $http_code");
        }
    }

    curl_close($ch);
}

function guardarEstadoEnBD($idApi, $nombreApi, $estado, $logMessage) {
    global $conn;

    $fecha = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO estado_apis (id_api, nombre_api, estado, horario, log_message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $idApi, $nombreApi, $estado, $fecha, $logMessage);

    if ($stmt->execute()) {
        writeToLog("Nuevo estado registrado exitosamente para $nombreApi.");
    } else {
        writeToLog("Error al insertar el estado: " . $stmt->error);
    }

    $stmt->close();
}

// Función para obtener las configuraciones de las APIs desde la base de datos
function obtenerConfiguracionesDeAPIs() {
    global $conn; // Asegúrate de que la conexión está disponible

    writeToLog("Obteniendo configuraciones de las APIs...");

    $sql = "SELECT id, api_name, api_url, api_user, api_password, execution_interval FROM api_logs";
    $result = $conn->query($sql);
    $apis = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $apis[] = $row;
        }
        writeToLog("Se han encontrado " . count($apis) . " APIs configuradas.");
    } else {
        writeToLog("No se encontraron APIs configuradas.");
    }

    return $apis;
}

// Bucle infinito para ejecutar la consulta a cada API según la frecuencia configurada
while (true) {
    // Registrar que estamos comenzando una nueva iteración del bucle
    writeToLog("Comenzando nueva iteración del bucle...");

    $apis = obtenerConfiguracionesDeAPIs();

    foreach ($apis as $api) {
        consultarAPI($api);

        // Registrar que se ha realizado la consulta a la API y que se va a esperar el intervalo
        writeToLog("Consulta realizada a la API: {$api['api_name']}. Esperando intervalo de ejecución...");

        // Convertir el intervalo de minutos a segundos
        $segundosDeEspera = $api['execution_interval'] * 60;

        // Pausa antes de ejecutar la siguiente consulta
        sleep($segundosDeEspera);
    }

    // Registrar que hemos completado una iteración
    writeToLog("Iteración completa. Esperando próxima ejecución.");
}

updateCronStatus('Detenido'); // Solo se ejecutará si el script termina, puedes manejar esto según tus necesidades.
?>
