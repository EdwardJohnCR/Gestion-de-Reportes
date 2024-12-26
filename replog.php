<?php
session_start();
include('./conn/conn.php');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the user's name from the database
    $stmt = $conn->prepare("SELECT `name` FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $user_name = $row['name'];
    }
?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bitácora de Seguridad</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="styles.css"> <!-- Enlace al archivo CSS -->

        <style>
            body {
                background-color: #2c3e50;
                color: #ecf0f1;
            }

            .container {
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                background-color: #34495e;
                border-radius: 8px;
            }

            .form-select,
            .form-control {
                background-color: #3b4b5c;
                color: #ecf0f1;
            }

            .btn-primary {
                background-color: #2980b9;
                border-color: #2980b9;
            }

            .btn-primary:hover {
                background-color: #3498db;
                border-color: #3498db;
            }

            .alert {
                margin-top: 20px;
            }

            .logo {
                width: 130px;
                height: auto;
                vertical-align: middle;
                margin-right: 10px;
            }

            .header-container {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 20px;
            }

            .header-title {
                margin: 0;
            }

            .file-actions {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 10px;
            }

            .btn-delete-file {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
                line-height: 1.5;
                border-radius: 0.2rem;
                background-color: #e74c3c;
                border-color: #e74c3c;
                color: #fff;
            }

            .btn-delete-file i {
                margin-right: 5px;
            }

            .btn-add-file {
                background-color: #27ae60;
                border-color: #27ae60;
                color: #fff;
                margin-top: 10px;
            }

            .btn-add-file i {
                margin-right: 5px;
            }

            #loadingIndicator p {
                color: #ecf0f1;
                font-size: 18px;
                margin-bottom: 10px;
            }
        </style>

        <script>
            function loadSites() {
                fetch('config.json')
                    .then(response => response.json())
                    .then(data => {
                        const siteSelect = document.getElementById('site');
                        siteSelect.innerHTML = ''; // Limpiar opciones existentes
                        siteSelect.appendChild(new Option('Seleccione Sitio', '')); // Opción por defecto

                        data.sites.forEach(site => {
                            siteSelect.appendChild(new Option(site, site));
                        });
                    })
                    .catch(error => console.error('Error al cargar sitios:', error));
            }

            document.addEventListener('DOMContentLoaded', loadSites);

            function addFileInput() {
                const fileInputsContainer = document.getElementById('fileInputsContainer');
                const newFileInputId = 'formfile_' + Date.now(); // Generar un ID único

                const fileInputDiv = document.createElement('div');
                fileInputDiv.className = 'file-actions';
                fileInputDiv.innerHTML = `
                <input class="form-control" type="file" name="formfile[]" id="${newFileInputId}" multiple>
                <button type="button" class="btn btn-delete-file" onclick="deleteFile('${newFileInputId}')">
                    <i class="fas fa-trash-alt"></i></button>
            `;
                fileInputsContainer.appendChild(fileInputDiv);
            }

            function deleteFile(inputId) {
                const fileInput = document.getElementById(inputId);
                fileInput.value = ''; // Borrar archivo seleccionado
                const parentDiv = fileInput.parentNode;
                parentDiv.remove(); // Borrar el campo de archivo completo
            }

            document.getElementById("logForm").addEventListener("submit", function(event) {
                // Mostrar el indicador de carga
                document.getElementById("loadingIndicator").style.display = "block";

                // Deshabilitar el botón de enviar para evitar múltiples envíos
                document.querySelector("button[type='submit']").disabled = true;
            });
        </script>
    </head>

    <body>
        <div class="container">
            <div class="container-fluid text-center text-md-left py-3">
                <div class="row align-items-center">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <img src="image/TotalSystem.png" alt="Logo" class="img-fluid logo">
                    </div>
                    <div class="col-12 col-md-4">
                        <h2 class="header-title">Reporte</h2>
                    </div>
                    <div class="col-12 col-md-4 mt-4 mt-md-0">
                        <h2>Bienvenido!..  </h2>
                        <h2><?= $user_name ?></h2>
                        <a href="./endpoint/logout.php" class="btn btn-danger mt-2">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
            <form id="logForm" action="process_form.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="officerName" class="form-label">Oficial en Turno</label>
                    <input type="text" class="form-control" id="officerName" name="officerName" value="<?= $user_name ?>" required readonly>
                </div>


                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="shift" class="form-label">Turno</label>
                        <select class="form-select" id="shift" name="shift" required>
                            <option value="Dia">Día</option>
                            <option value="Tarde">Mixto</option>
                            <option value="Noche">Noche</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="site" class="form-label">Sitio</label>
                        <select class="form-select" id="site" name="site" required>
                            <option value="" disabled selected>Seleccione Sitio</option>
                            <?php foreach ($sites as $site): ?>
                                <option value="<?= htmlspecialchars($site) ?>">
                                    <?= htmlspecialchars($site) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="incidentReport" class="form-label">Reporte de Incidentes</label>
                    <textarea class="form-control" id="incidentReport" name="incidentReport" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="formfile">Adjunte Imagen o Archivo</label>
                    <div id="fileInputsContainer">
                        <div class="file-actions">
                            <input class="form-control" type="file" name="formfile[]" id="formfile" multiple>
                            <button type="button" class="btn btn-delete-file" onclick="clearFileInput('formfile')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-add-file" onclick="addFileInput()">
                        <i class="fas fa-plus"></i> Agregar otro archivo
                    </button>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirmSubmission" id="confirmSubmission"
                            required>
                        <div><label class="form-check-label" for="confirmSubmission">Confirmar envío</label>
                        </div>
                    </div>

                    <!-- Indicador de Carga -->
                    <div id="loadingIndicator" style="display: none; text-align: center;">
                        <p>Enviando...</p>
                        <img src="loading.gif" alt="Cargando" style="width: 50px; height: 50px;">
                    </div>

                    <div id="btnSave">
                        <button type="submit" class="btn btn-primary">Enviar Reporte</button>
                    </div>
            </form>


        </div>
    </body>

    </html>

<?php
} else {
    header("Location: youserver.com/");
}
?>