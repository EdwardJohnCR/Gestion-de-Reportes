<?php
session_start();
include('./conn/conn.php');

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

// Verificar si el ID del usuario está presente en la URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Obtener el nombre del usuario desde la base de datos
    $stmt = $conn->prepare("SELECT `name` FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $user_name = $row['name'];
    }

    // Manejo del formulario de cambio de contraseña
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verificar que las contraseñas coincidan
        if ($new_password !== $confirm_password) {
            echo "<script>alert('Las contraseñas no coinciden.');</script>";
        } else {
            // Validar la longitud de la nueva contraseña
            if (strlen($new_password) < 8) {
                echo "<script>alert('La contraseña debe tener al menos 8 caracteres.');</script>";
            } else {
                // Hash de la nueva contraseña
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Actualizar la contraseña en la base de datos
                $update_stmt = $conn->prepare("UPDATE `tbl_user` SET `password` = :hashed_password WHERE `tbl_user_id` = :user_id");
                $update_stmt->bindParam(':hashed_password', $hashed_password);
                $update_stmt->bindParam(':user_id', $user_id);

                if ($update_stmt->execute()) {
                    // Redireccionar con mensaje de éxito
                    header("Location: change_password.php?success=true&name=" . urlencode($user_name));
                    exit();
                } else {
                    echo "<script>alert('Error al cambiar la contraseña.');</script>";
                }
            }
        }
    }
} 
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (opcional, si usas íconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Estilos específicos para el formulario de cambio de contraseña -->
    <style>
        /* Fondo con imagen */
        body {
            /* background-image: url("./image/TotalSystem.png"); */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow: hidden;
            height: 100vh;
            background-color: #2c3e50; /* Color de respaldo en caso de que la imagen no cargue */
            color: #ecf0f1;
        }

        /* Contenedor principal centrado */
        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.6);
            min-height: 100vh;
        }

        /* Estilo del contenedor del formulario */
        .password-container {
            max-width: 400px;
            padding: 30px;
            background-color: #34495e; /* Coincide con el fondo de la bitácora */
            border-radius: 8px;
            box-shadow: rgba(255, 255, 255, 0.24) 0px 3px 8px;
            color: #ecf0f1;
        }

        /* Encabezado con logo y título */
        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .header-container .logo {
            width: 130px;
            height: auto;
            margin-right: 10px;
        }

        .header-container .header-title {
            margin: 0;
            font-size: 2rem;
            color: #ecf0f1;
        }

        /* Estilos de los labels */
        .form-label {
            color: #ecf0f1;
        }

        /* Estilos de los inputs */
        .form-control {
            background-color: #3b4b5c;
            color: #ecf0f1;
            border: none;
        }

        .form-control:focus {
            background-color: #3b4b5c;
            color: #ecf0f1;
            box-shadow: none;
        }

        /* Botones */
        .btn-primary {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .btn-primary:hover {
            background-color: #3498db;
            border-color: #3498db;
        }

        .btn-primary:focus {
            box-shadow: none;
        }

        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .btn-danger:focus {
            box-shadow: none;
        }

        /* Ajustes responsivos */
        @media (max-width: 768px) {
            .password-container {
                width: 90%;
            }

            .header-container .header-title {
                font-size: 1.5rem;
            }
        }
    </style>    
</head>

<body>
    <div class="main container-fluid">
        <div class="row w-100">
            <div class="col-12">
                <div class="password-container mx-auto">
                    <!-- Encabezado con logo y título -->
                    <div class="header-container mb-4">
                        <img src="image/TotalSystem.png" alt="Logo" class="logo">
                        <h1 class="header-title">Cambiar Contraseña</h1>
                    </div>

                    <!-- Formulario de cambio de contraseña -->
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva Contraseña:</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                        <a href="view-user.php" class="btn btn-danger w-100 mt-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS y dependencias (Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script opcional para mostrar mensajes de éxito -->
    <script>
    <?php if (isset($_GET['success']) && $_GET['success'] === 'true' && isset($_GET['name'])): ?>
        var userName = '<?= htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8') ?>';  // Obtener el nombre del usuario desde la URL
        alert('¡Contraseña de "' + userName + '" se cambio con éxito!');
        window.location.href = 'view-user.php';  // Redireccionar después de la alerta
    <?php endif; ?>
</script>
</body>

</html>
<?php
} else {
    header("Location: http://ttscr.com/login/");
}
?>
