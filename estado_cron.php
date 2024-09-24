<?php
// Incluir el archivo de configuración
$config = include('config.php');

// Función para verificar si el cron está corriendo
function isCronRunning() {
    $output = [];
    $cronScript = 'cron.php';

    // Obtener la lista de procesos en ejecución
    exec("tasklist /FI \"IMAGENAME eq php.exe\" /FO LIST", $output);

    foreach ($output as $line) {
        if (strpos($line, $cronScript) !== false) {
            return true;
        }
    }

    return false;
}

// Función para iniciar el cron
function startCron() {
    exec("start /B php cron.php");
    writeToLog("Se intentó iniciar el cron.");
}

// Función para detener el cron
function stopCron() {
    exec("taskkill /FI \"IMAGENAME eq php.exe\" /F");
    writeToLog("Se intentó detener el cron.");
}

// Manejar la solicitud AJAX para iniciar o detener el cron
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['start_cron'])) {
        startCron();
        echo json_encode(['status' => 'started']);
        exit();
    }

    if (isset($_POST['stop_cron'])) {
        stopCron();
        echo json_encode(['status' => 'stopped']);
        exit();
    }

    // Manejar la solicitud AJAX para actualizar el log
    if (isset($_POST['update_log'])) {
        $logFile = 'cron.log';
        $lastReadLine = intval($_POST['last_line']); // Obtener el número de la última línea leída

        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Obtener las nuevas líneas (desde la última línea leída hasta el final)
            $newLines = array_slice($lines, $lastReadLine);

            // Preparar la respuesta con las nuevas líneas y el nuevo offset
            echo json_encode([
                'new_lines' => nl2br(implode("\n", $newLines)),
                'new_offset' => count($lines)  // Enviar el nuevo número de línea
            ]);
        } else {
            echo json_encode(['new_lines' => 'No hay registros de log disponibles.', 'new_offset' => 0]);
        }
        exit();
    }
}

$cronRunning = isCronRunning();
?>





<!DOCTYPE html>
<html>
<head>
    <title>Panel API - Estado del Cron</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var lastReadLine = 0; // Mantener el número de la última línea leída

        function startCron() {
            $.post("", { start_cron: true }, function(response) {
                if (response.status === 'started') {
                    location.reload();
                }
            }, "json");
        }

        function stopCron() {
            $.post("", { stop_cron: true }, function(response) {
                if (response.status === 'stopped') {
                    location.reload();
                }
            }, "json");
        }

        // Función para actualizar el log dinámicamente
        function updateLog() {
            $.post("", { update_log: true, last_line: lastReadLine }, function(response) {
                if (response.new_lines) {
                    // Añadir las nuevas líneas al principio del log
                    $('#logContent').prepend(response.new_lines);
                    
                    // Actualizar el nuevo offset de líneas leídas
                    lastReadLine = response.new_offset;
                }
            }, "json");
        }

        // Actualizar el log cada 10 segundos para reducir la carga
        setInterval(updateLog, 10000);

        // Actualizar inmediatamente después de iniciar el cron
        $(document).ready(function() {
            updateLog();
        });
    </script>
</head>
<body class="bg-light">

<?php
// Incluir el archivo de menu
$config = include('menu.php');
?>
<div class="container">

    <h1>Estado del Cron</h1>

    <?php if ($cronRunning): ?>
        <p style="color:green;">El cron está corriendo.</p>
    <?php else: ?>
        <p style="color:red;">El cron no está corriendo.</p>
    <?php endif; ?>

    <button onclick="startCron()">Iniciar Cron</button>
    <button onclick="stopCron()">Detener Cron</button>

    <h2>Log de Ejecución</h2>
    <div style="border:1px solid #ccc; padding:10px; height:300px; overflow:auto;">
        <div id="logContent">
            <!-- El contenido del log será actualizado aquí -->
        </div>
    </div>

</div>

<?php
// Incluir el archivo de menu
$config = include('footer.php');
?>

</body>
</html>
