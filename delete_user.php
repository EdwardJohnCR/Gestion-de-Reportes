                                                    <?php
session_start();
include('./conn/conn.php');

// Verificar si se ha recibido un ID de usuario para borrar
if (isset($_GET['id'])) {
    $userID = $_GET['id'];

    // Preparar y ejecutar la consulta para borrar el usuario
    $stmt = $conn->prepare("DELETE FROM `tbl_user` WHERE `tbl_user_id` = :user_id");
    $stmt->bindParam(':user_id', $userID);

    if ($stmt->execute()) {
        // Redirigir con un mensaje de éxito
        header("Location: view-user.php?mensaje=borrado");
        exit();
    } else {
        // Redirigir con un mensaje de error si la eliminación falla
        header("Location: view-user.php?mensaje=error");
        exit();
    }
} else {
    // Redirigir si no se proporciona un ID válido
    header("Location: view-user.php?mensaje=error");
    exit();
}
?>
