<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor de Colecciones de Postman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editor de Colecciones de Postman</h1>
        
        <!-- Formulario para agregar o editar una solicitud -->
        <form id="form-request" method="POST" action="guardar_request.php">
            <input type="hidden" id="request_id" name="request_id">
            <div class="mb-3">
                <label for="request_name" class="form-label">Nombre de la Solicitud</label>
                <input type="text" class="form-control" id="request_name" name="request_name" required>
            </div>
            <div class="mb-3">
                <label for="method" class="form-label">Método</label>
                <select class="form-control" id="method" name="method" required>
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="DELETE">DELETE</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="url" class="form-label">URL</label>
                <input type="text" class="form-control" id="url" name="url" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

        <!-- Tabla para listar las solicitudes -->
        <h2 class="mt-5">Solicitudes Guardadas</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Método</th>
                    <th>URL</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $db = new PDO('mysql:host=localhost;dbname=postman_db', 'root', 'admin');
                $stmt = $db->query("SELECT * FROM postman_requests");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>{$row['request_name']}</td>
                        <td>{$row['method']}</td>
                        <td>{$row['url']}</td>
                        <td>
                            <button class='btn btn-warning btn-edit' data-id='{$row['id']}' data-name='{$row['request_name']}' data-method='{$row['method']}' data-url='{$row['url']}'>Editar</button>
                            <a href='eliminar_request.php?id={$row['id']}' class='btn btn-danger'>Eliminar</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Script para llenar el formulario con los datos existentes
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('request_id').value = this.getAttribute('data-id');
                document.getElementById('request_name').value = this.getAttribute('data-name');
                document.getElementById('method').value = this.getAttribute('data-method');
                document.getElementById('url').value = this.getAttribute('data-url');
            });
        });
    </script>
</body>
</html>
