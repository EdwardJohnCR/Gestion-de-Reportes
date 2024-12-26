<?php
session_start();
include('./conn/conn.php');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the user's role and name from the database
    $stmt = $conn->prepare("SELECT `name`, `role` FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);  // Aseguramos que el user_id sea un entero
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $user_name = $row['name'];
        $user_role = $row['role'];

        // Si el rol no es 'admin', redirigir a la página de admin
        if ($user_role !== 'admin') {
            header("Location: youserver.com/edit-bita.php");
            exit(); // Asegurarse de que el script se detiene después de la redirección
        

    // Fetch the user's name from the database
    $stmt = $conn->prepare("SELECT `name` FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $user_name = $row['name'];
    }
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Datos Bitacora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script>
        function loadConfig() {
            fetch('config.json')
                .then(response => response.json())
                .then(data => {
                    // Cargar el contador de bitácora
                    document.getElementById('counter').textContent = data.bitacora;

                    // Cargar los sitios
                    const siteSelect = document.getElementById('siteSelect');
                    siteSelect.innerHTML = '';
                    data.sites.forEach(site => {
                        siteSelect.innerHTML += `<option value="${site}">${site}</option>`;
                    });

                    // Cargar los correos electrónicos
                    const emailList = document.getElementById('emailList');
                    emailList.innerHTML = '';
                    data.emails.forEach(email => {
                        emailList.innerHTML += `
                            <div class="email-item mb-2">
                                <input type="text" class="form-control" value="${email}" readonly>
                                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeEmail(this)">Eliminar</button>
                            </div>
                        `;
                    });
                })
                .catch(error => console.error('Error al cargar la configuración:', error));
        }

        function saveConfig() {
            const siteOptions = Array.from(document.getElementById('siteSelect').options)
                .map(option => option.value)
                .filter(value => value);

            const emails = Array.from(document.querySelectorAll('#emailList input'))
                .map(input => input.value);

            const counter = document.getElementById('counter').textContent;

            fetch('update_config.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ bitacora: counter, sites: siteOptions, emails: emails })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Configuración guardada con éxito.');
                } else {
                    alert('Error al guardar la configuración: ' + (result.message || 'No se pudo completar la operación.'));
                }
            })
            .catch(error => alert('Error al guardar configuración: ' + error.message));
        }

        function addSite() {
            const newSite = prompt('Ingrese el nuevo sitio:');
            if (newSite) {
                const siteSelect = document.getElementById('siteSelect');
                const exists = Array.from(siteSelect.options).some(option => option.value === newSite);
                if (!exists) {
                    siteSelect.innerHTML += `<option value="${newSite}">${newSite}</option>`;
                } else {
                    alert('El sitio ya existe.');
                }
            }
        }

        function removeSite() {
            const siteSelect = document.getElementById('siteSelect');
            siteSelect.remove(siteSelect.selectedIndex);
        }

        function addEmail() {
            const newEmail = prompt('Ingrese el nuevo correo:');
            if (newEmail) {
                const emailList = document.getElementById('emailList');
                const exists = Array.from(emailList.querySelectorAll('input')).some(input => input.value === newEmail);
                if (!exists) {
                    emailList.innerHTML += `
                        <div class="email-item mb-2">
                            <input type="text" class="form-control" value="${newEmail}" readonly>
                            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeEmail(this)">Eliminar</button>
                        </div>
                    `;
                } else {
                    alert('El correo ya existe.');
                }
            }
        }

        function removeEmail(button) {
            const emailList = document.getElementById('emailList');
            emailList.removeChild(button.parentElement);
        }

        document.addEventListener('DOMContentLoaded', loadConfig);
    </script>
</head>
<body>
    <div class="container">
        <h2>Editar Datos Bitacora</h2>
        <form onsubmit="event.preventDefault(); saveConfig();">
            <div class="mb-3">
                <label for="siteSelect" class="form-label">Sitios Agregados</label>
                <select id="siteSelect" class="form-select" multiple>
                    <!-- Opciones cargadas dinámicamente -->
                </select>
                <button type="button" class="btn btn-primary mt-2" onclick="addSite()">Agregar Sitio</button>
                <button type="button" class="btn btn-danger mt-2" onclick="removeSite()">Eliminar</button>
            </div>
            <div class="mb-3">
                <label for="emailList" class="form-label">Correos Electrónicos que se enviara el reporte</label>
                <div id="emailList">
                    <!-- Correos cargados dinámicamente -->
                </div>
                <button type="button" class="btn btn-primary mt-2" onclick="addEmail()">Agregar Correo</button>
            </div>
            <div class="mb-3">
                <label for="counter" class="form-label">Contador de Bitácora</label>
                <span id="counter">0</span>
            </div>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="lobby.php" class="btn btn-primary">Regresar</a>
            <a href="./endpoint/logout.php" class="btn btn-danger">Salir</a>

        </form>
    </div>

</body>
</html>
<?php
} else {
    header("Location: youserver.com/");
}
?>