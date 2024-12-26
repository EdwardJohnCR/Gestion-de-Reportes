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
    <title>Error en el Envío</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            text-align: center;
        }
        .btn-primary {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .btn-primary:hover {
            background-color: #3498db;
            border-color: #3498db;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Error en el Envío  1</h2>
        <p>Hubo un problema al enviar tu bitácora. Por favor, intenta nuevamente.</p>
        <a href="replog.php" class="btn btn-primary">Volver al Formulario</a>
    </div>
</body>
</html>

<?php
} else {
    header("Location: youserver.com/");
}

?>