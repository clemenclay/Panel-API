<?php

// Ruta al archivo execute_requests.php
require 'execute_requests.php';

// Llamar a la función que ejecuta todas las colecciones
executeAllCollections();

// Dentro de run_requests.php
file_put_contents('cron_execution_log.txt', 'Ejecutado en: ' . date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND);


?>
