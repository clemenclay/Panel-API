<x-app-layout>

  <div class="container mt-5">

        <table id="logsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Método</th>
                    <th>URL</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Autenticación</th>
                    <!-- <th>Cuerpo de Solicitud</th> -->
                    <th>Respuesta</th>
                </tr>
            </thead>
            <tbody>
                <?php


                // Obtener las variables de entorno del archivo .env
                $dbHost = env('DB_HOST');
                $dbName = env('DB_DATABASE');
                $dbUser = env('DB_USERNAME');
                $dbPassword = env('DB_PASSWORD');


                try {
                    // Conexión a la base de datos
                    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Consulta para obtener solo el último registro por cada solicitud
                    $stmt = $db->query("
                        SELECT pr.request_name, pr.method, pr.url, pr.auth, pr.body, rl.status, rl.execution_time, rl.response
                        FROM request_logs rl
                        JOIN postman_requests pr ON rl.request_id = pr.id
                        WHERE rl.execution_time IN (
                            SELECT MAX(execution_time)
                            FROM request_logs
                            GROUP BY request_id
                        )
                        ORDER BY rl.execution_time DESC
                    ");

                    // Mostrar los resultados
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Decodificar JSON para autenticación y cuerpo de solicitud
                        $auth = json_decode($row['auth'], true);
                        $body = json_decode($row['body'], true);

                        // Verificar el tipo de autenticación y formatear para mostrar
                        if (!empty($auth)) {
                            if (isset($auth['type']) && $auth['type'] == 'basic' && isset($auth['basic'])) {
                                $username = isset($auth['basic'][1]['value']) ? $auth['basic'][1]['value'] : 'Desconocido';
                                $authDisplay = 'Usuario: ' . $username;
                            } else {
                                $authDisplay = 'Autenticación no básica';
                            }
                        } else {
                            $authDisplay = 'Sin autenticación';
                        }

                        // Formatear el cuerpo de la solicitud para mostrar
                        $bodyDisplay = json_encode($body, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

                        // Botón para abrir el modal con la respuesta
                        $modalId = 'modal-' . uniqid();

                        // Definir el color del badge según el código de estado
                        $badgeClass = '';
                        switch ($row['status']) {
                            case 200:
                                $badgeClass = 'bg-success'; // Verde para 200
                                break;
                            case 400:
                            case 401:
                                $badgeClass = 'bg-warning'; // Naranja para 400 y 401
                                break;
                            case 404:
                            case 302:
                                $badgeClass = 'bg-danger'; // Rojo para 404 y 302
                                break;
                            default:
                                $badgeClass = 'bg-danger'; // Rojo para otros códigos de error
                                break;
                        }

                        echo "<tr>
                            <td>{$row['request_name']}</td>
                            <td>{$row['method']}</td>
                            <td>{$row['url']}</td>
                            <td><span class='badge $badgeClass'>{$row['status']}</span></td>
                            <td>{$row['execution_time']}</td>
                            <td>{$authDisplay}</td>

                            <td>
                                <button type='button' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#$modalId'>
                                    Detalle
                                </button>

                                <!-- Modal -->
                                <div class='modal fade' id='$modalId' tabindex='-1' aria-labelledby='{$modalId}Label' aria-hidden='true'>
                                  <div class='modal-dialog modal-lg'>
                                    <div class='modal-content'>
                                      <div class='modal-header'>
                                        <h5 class='modal-title' id='{$modalId}Label'>Respuesta de la Solicitud</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                      </div>
                                      <div class='modal-body' id='json-input'>
                                        <pre id='response-{$modalId}' style='display:none;'>" . htmlspecialchars($row['response']) . "</pre>
                                        <div id='response-display-{$modalId}'></div>
                                      </div>
                                      <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </td>
                        </tr>";
                    }
                } catch (PDOException $e) {
                    echo "Error en la consulta: " . $e->getMessage();
                    die('Error de conexión: ' . $e->getMessage());
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

    <!-- Inicialización de DataTables -->
    <script>
        $(document).ready(function() {
            $('#logsTable').DataTable({
                "order": [[ 4, "desc" ]], // Ordenar por "Tiempo de Respuesta" por defecto
                "pageLength": 10, // Mostrar 10 registros por página
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/2.1.8/i18n/es-MX.json" // Traducción al español
                }
            });

            // Formatear la respuesta al abrir el modal
            $('button[data-bs-toggle="modal"]').on('click', function() {
                var modalId = $(this).data('bs-target');
                var responseContent = $('#response-' + modalId.substring(1)).text();

                // Usar JSON.stringify para formatear la salida
                $('#response-display-' + modalId.substring(1)).text(JSON.stringify(JSON.parse(responseContent), null, 2));
                $('#response-display-' + modalId.substring(1)).css('white-space', 'pre'); // Mantener formato
            });
        });
    </script>
</x-app-layout>
