<x-app-layout>



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



                // Obtener las variables de entorno del archivo .env
                $dbHost = env('DB_HOST');
                $dbName = env('DB_DATABASE');
                $dbUser = env('DB_USERNAME');
                $dbPassword = env('DB_PASSWORD');


                // Conexión a la base de datos
                $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


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


    <div class="container mt-5">
        <h1>Subir Colección de Postman</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('save.postman') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="postman_collection" class="form-label">Selecciona un archivo JSON de Postman:</label>
                <input type="file" class="form-control" name="postman_collection" accept=".json" required>
            </div>
            <button type="submit" class="btn btn-primary">Cargar Colección</button>
        </form>
    </div>
</x-app-layout>
