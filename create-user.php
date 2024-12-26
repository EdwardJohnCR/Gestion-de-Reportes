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
            header("Location: youserver.com/view-user.php");
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
        <title>Crear Usuarios</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="styles.css"> <!-- Enlace al archivo CSS -->
        <style>
            .col-12.col-md-4.mt-4.mt-md-0 {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                /* Alinea los botones a la derecha */
            }

            .btn {
                margin-bottom: 10px;
                /* Separa los botones entre sí */
            }

            @media (max-width: 768px) {
                .col-12.col-md-4.mt-4.mt-md-0 {
                    align-items: center;
                    /* Centra los botones en pantallas pequeñas */
                }
            }
        </style>
    </head>

    <body>
        <div class="main">
            <div class="container">
                <!-- usuario llamado desde la base de datos -->
                <div class="container-fluid text-center text-md-left py-3">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-4 mb-3 mb-md-0">
                            <img src="image/TotalSystem.png" alt="Logo" class="img-fluid logo">
                        </div>
                        <div class="col-12 col-md-4">
                            <h2>Bienvenido!..</h2>
                            <h2><?= $user_name ?></h2>
                        </div>
                        <div class="col-12 col-md-4 mt-4 mt-md-0">
                            <a href="./endpoint/logout.php" class="btn btn-danger mt-2">Cerrar Sesión</a>
                            <a href="lobby.php" class="btn btn-primary">Regresar</a>
                        </div>
                    </div>
                </div>


                <div id="registrationForm" class="form-container hidden">
                    <h3 class="text-center mb-4">Registrar un Usuario</h3>
                    <form action="./endpoint/add-user.php" method="POST">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nombre Completo:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group mb-3">
                            <label for="role" class="form-label">Rol:</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">-seleccionar-</option>
                                <option value="admin">Admin</option>
                                <option value="user">Usuari@</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="registerUsername" class="form-label">Usuario:</label>
                            <input type="text" class="form-control" id="registerUsername" name="username">
                        </div>
                        <div class="form-group mb-3">
                            <label for="registerPassword" class="form-label">Contraseña:</label>
                            <input type="password" class="form-control" id="registerPassword" name="password">
                        </div>
                        <div class="form-group mb-4">
                            <label for="confirmPassword" class="form-label">Confirmar Contraseña:</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Registrar</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bootstrap and JS scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>

<?php
} else {
    header("Location: youserver.com/");
}
?>