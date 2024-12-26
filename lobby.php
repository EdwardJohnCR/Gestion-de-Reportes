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
            header("Location: youserver.com/lobby.php");
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
        <title>Panel Administrador</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="styles.css"> <!-- Enlace al archivo CSS -->
    </head>

    <body>
        <div class="container">
            <div class="header-container">
                <h3 class="header-title">Bienvenido!.. <?= htmlspecialchars($user_name) ?></h3>
            </div>
            <!-- <div class="title2">
                <h3> Agrega, Actualiza o Elimina Datos</h3>
            </div> -->
            <div class="d-grid gap-3 col-4 mx-auto mt-5">
                <a href="create-user.php" class="btn btn-primary">Agregar Usuarios</a>
                <a href="view-user.php" class="btn btn-primary">Ver Usuarios</a>
                <a href="edit-bita.php" class="btn btn-primary">Editar Bitácora</a>
                <a href="lobby.php" class="btn btn-primary">Datos Biracora</a>

                <a href="./endpoint/logout.php" class="btn btn-danger">Salir</a>
            </div>
        </div>
    </body>

    </html>

<?php
} else {
    header("Location: youserver.com/");
}
?>