<x-app-layout>
<div class="container mt-5">
    <button data-bs-toggle="modal" class="btn btn-warning btn-edit" data-bs-target="#modal-subir">Subir Colección de Postman</button>

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

    <!-- Modal para subir colección de Postman -->
    <div class="modal fade" id="modal-subir" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subir Colección de Postman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('save.postman') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="postman_collection" class="form-label">Selecciona un archivo JSON de Postman:</label>
                            <input type="file" class="form-control" name="postman_collection" accept=".json" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cargar Colección</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar una solicitud -->
    <div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modal-edit" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Solicitud</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de solicitudes con DataTables -->
 <div class="table-responsive mt-5">
    <table id="solicitudesTable" class="table table-bordered table-striped">
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
                $dbHost = env('DB_HOST');
                $dbName = env('DB_DATABASE');
                $dbUser = env('DB_USERNAME');
                $dbPassword = env('DB_PASSWORD');

                $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $db->query("SELECT * FROM postman_requests");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>{$row['request_name']}</td>
                        <td>{$row['method']}</td>
                        <td>{$row['url']}</td>
                        <td>
                            <button data-bs-toggle='modal' data-bs-target='#modal-edit'
                            class='btn btn-secondary btn-edit'
                            data-id='{$row['id']}' data-name='{$row['request_name']}'
                            data-method='{$row['method']}' data-url='{$row['url']}'>Editar</button>

                            <button class='btn btn-danger btn-delete'
                                    data-bs-toggle='modal'
                                    data-bs-target='#modal-delete'
                                    data-id='{$row['id']}'
                                    data-name='{$row['request_name']}'>
                                Eliminar
                            </button>
                        </td>
                    </tr>";
                }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar la solicitud "<span id="delete-request-name"></span>"?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="GET" action="">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>


</div>

<!-- jQuery, Bootstrap 5, DataTables JS y configuración -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#solicitudesTable').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 10,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-MX.json"
            }
        });

        // Formulario de edición dentro del modal
        $('.btn-edit').on('click', function() {
            $('#request_id').val($(this).data('id'));
            $('#request_name').val($(this).data('name'));
            $('#method').val($(this).data('method'));
            $('#url').val($(this).data('url'));
        });
    });
</script>



<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteModal = document.getElementById('modal-delete');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        // Botón que abre el modal
        var button = event.relatedTarget;
        // Extrae los datos del botón
        var requestId = button.getAttribute('data-id');
        var requestName = button.getAttribute('data-name');

        // Actualiza el contenido del modal
        var modalRequestName = deleteModal.querySelector('#delete-request-name');
        modalRequestName.textContent = requestName;

        // Actualiza la acción del formulario con la URL de eliminación
        var deleteForm = document.getElementById('deleteForm');
        deleteForm.action = '/eliminar/' + requestId;
    });
});
</script>


</x-app-layout>
