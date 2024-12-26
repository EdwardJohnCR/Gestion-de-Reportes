<?php
session_start();
include('./conn/conn.php');

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Obtener el nombre del usuario desde la base de datos
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
        <title>Usuarios Registrados</title>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="http://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="styles.css"> <!-- Enlace al archivo CSS -->
        <script src="http://kit.fontawesome.com/21f49d14bf.js" crossorigin="anonymous"></script>
        <style>
            body {
                background-color: #2c3e50;
                color: #ecf0f1;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }

            .main {
                width: 100%;
                max-width: 1000px;
                padding: 20px;
                background-color: #34495e;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .title-container {
                text-align: center;
                margin-bottom: 20px;
            }

            .title-container h1,
            .title-container h2 {
                color: #ecf0f1;
            }

            .users-container {
                background-color: #3b4b5c;
                padding: 20px;
                border-radius: 8px;
            }

            .table thead th {
                background-color: #2980b9;
                color: #ffffff;
            }

            .table tbody td {
                background-color: #ecf0f1;
                color: #2c3e50;
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

            .btn-edit {
                background-color: #27ae60;
                border-color: #27ae60;
                color: #ffffff;
            }

            .btn-edit:hover {
                background-color: #2ecc71;
            }

            .btn-delete {
                background-color: #e74c3c;
                border-color: #e74c3c;
            }

            .btn-delete:hover {
                background-color: #c0392b;
            }

            .btn-danger {
                margin-left: 10px;
            }
        </style>
    </head>

    <body>

        <div class="main">
            <div class="title-container">
                <h1>Administrar Usuarios</h1>
                <h2><?= $user_name ?></h2>
                <div class="text-center">
                    <a href="lobby.php" class="btn btn-primary">Regresar</a>
                    <a href="./endpoint/logout.php" class="btn btn-danger">Cerrar Sesión</a>
                </div>
            </div>

            <div class="users-container">
                <h2>Lista de Usuarios</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Contraseña</th>
                                <th scope="col">Rol</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM `tbl_user`");
                            $stmt->execute();
                            $result = $stmt->fetchAll();

                            foreach ($result as $row) {
                                $userID = $row['tbl_user_id'];
                                $name = $row['name'];
                                $username = $row['username'];
                                $password = $row['password'];
                                $role = $row['role'];
                            ?>
                                <tr>
                                    <td><?= $userID ?></td>
                                    <td><?= $name ?></td>
                                    <td><?= $username ?></td>
                                    <td><?= $password ?></td>
                                    <td><?= $role ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?= $userID ?>" class="btn btn-edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <a href="delete_user.php?id=<?= $userID ?>" class="btn btn-delete" onclick="return confirm('¿Estás seguro de que quieres borrar este usuario?');"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </body>

    </html>

<?php
} else {
    header("Location: http://ttscr.com/roles-login/");
}
?>