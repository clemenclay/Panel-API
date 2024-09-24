<?php

// Incluir el archivo de configuración
$config = include('config.php');

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta para obtener el último estado de cada API
$sql = "
    SELECT e.*
    FROM estado_apis e
    INNER JOIN (
        SELECT nombre_api, MAX(horario) AS max_horario
        FROM estado_apis
        GROUP BY nombre_api
    ) b ON e.nombre_api = b.nombre_api AND e.horario = b.max_horario
    ORDER BY e.horario DESC
";
?>

<body class="bg-light">

<?php
// Incluir el archivo de menú
$config = include('menu.php');
?>

<div class="container">

<h1>Estado de las APIs</h1>

<?php
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-striped table-bordered'>";
    echo "<thead class='table-dark'>";
    echo "<tr><th>API</th><th>Estado</th><th>Fecha</th><th class='text-end'>Acciones</th></tr>";
    echo "</thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nombre_api']) . "</td>"; // Cambia 'api_name' a 'nombre_api'
        echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
        echo "<td>" . htmlspecialchars($row['horario']) . "</td>";
        echo "<td class='text-end'>";
        // Botón para editar
        echo "<a href='edit_api_log.php?id=" . $row['id_api'] . "' class='btn btn-sm btn-primary me-2'>Editar</a>"; 
        // Botón para eliminar
        echo "<a href='delete_api.php?id=" . $row['id_api'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Estás seguro que quieres eliminar esta API?\");'>Eliminar</a>";
        // Botón para ver detalles (abrir modal)
        echo "<button type='button' class='btn btn-sm btn-info' data-bs-toggle='modal' data-bs-target='#logModal" . $row['id_api'] . "'>Ver Log</button>";
        echo "</td>";
        echo "</tr>";

        // Modal para mostrar el log_message
        echo "
        <div class='modal fade' id='logModal" . $row['id_api'] . "' tabindex='-1' aria-labelledby='logModalLabel" . $row['id_api'] . "' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='logModalLabel" . $row['id_api'] . "'>Log de " . htmlspecialchars($row['nombre_api']) . "</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <div class='modal-body'>
                        <p>" . htmlspecialchars($row['log_message']) . "</p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        ";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<div class='alert alert-warning' role='alert'>No se encontraron registros.</div>";
}

$conn->close();
?>

<?php
// Incluir el archivo de pie de página
$config = include('footer.php');
?>



</body>
</html>
