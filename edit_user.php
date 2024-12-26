<?php
session_start();
include('./conn/conn.php');

if (isset($_POST['editar_usuario'])) {
    $userID = $_POST['user_id'];
    $name = $_POST['name'];
    $username = $_POST['username'];

    $role = $_POST['role'];

    // Actualización del usuario en la base de datos
    $stmt = $conn->prepare("UPDATE `tbl_user` SET `name` = :name, `username` = :username, `role` = :role WHERE `tbl_user_id` = :user_id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':username', $username);

    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':user_id', $userID);

    if ($stmt->execute()) {
        header("Location: view-user.php?mensaje=editado");
    } else {
        header("Location: view-user.php?mensaje=error");
    }
} else {
    $userID = $_GET['id'];

    // Consulta del usuario desde la base de datos
    $stmt = $conn->prepare("SELECT * FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
    $stmt->bindParam(':user_id', $userID);
    $stmt->execute();
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: view-user.php?mensaje=error");
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

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

        .container {
            background-color: #34495e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #ecf0f1;
        }

        .form-label {
            color: #ecf0f1;
        }

        .form-control {
            background-color: #838d97;
            color: #ecf0f1;
            border: none;
        }

        .form-control:focus {
            background-color: #838d97;
            color: #ecf0f1;
            box-shadow: none;
        }

        .btn-primary {
            background-color: #2980b9;
            border-color: #2980b9;
        }

        .btn-primary:hover {
            background-color: #3498db;
            border-color: #3498db;
        }

        .btn-secondary {
            background-color: #95a5a6;
            border-color: #95a5a6;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
            border-color: #7f8c8d;
        }

        .btn-primary:focus,
        .btn-secondary:focus {
            box-shadow: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Editar Usuario</h2>
        <form action="edit_user.php" method="POST">
            <input type="hidden" name="user_id" value="<?= $user['tbl_user_id'] ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="name" value="<?= $user['name'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Usuario:</label>
                <input type="text" class="form-control" name="username" value="<?= $user['username'] ?>" required>
            </div>



            <div class="mb-3">
                <label for="role" class="form-label">Rol:</label>
                <select class="form-select" id="role" name="role">
                    <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="user" <?= ($user['role'] === 'user') ? 'selected' : '' ?>>Usuari@</option>
                </select>
            </div>


            <button type="submit" class="btn btn-primary w-100" name="editar_usuario">Guardar Cambios</button>
            <a href="view-user.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
        </form>
    </div>
</body>

</html>