<?php
include('../conn/conn.php');

if (isset($_POST['name'], $_POST['role'], $_POST['username'], $_POST['password'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Verificar si el nombre ya existe en la base de datos
        $stmt = $conn->prepare("SELECT `name` FROM `tbl_user` WHERE `name` = :name");
        $stmt->execute(['name' => $name]);

        $nameExist = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($nameExist)) {
            // Comenzar la transacción
            $conn->beginTransaction();

            // Hashear la contraseña antes de guardarla
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insertar el nuevo usuario en la base de datos
            $insertStmt = $conn->prepare("INSERT INTO `tbl_user` (`name`, `role`, `username`, `password`) VALUES (:name, :role, :username, :password)");
            $insertStmt->bindParam('name', $name, PDO::PARAM_STR);
            $insertStmt->bindParam('role', $role, PDO::PARAM_STR);
            $insertStmt->bindParam('username', $username, PDO::PARAM_STR);
            $insertStmt->bindParam('password', $hashed_password, PDO::PARAM_STR); // Usamos el hash de la contraseña

            $insertStmt->execute();

            // Confirmar la transacción
            $conn->commit();

            echo "
            <script>
                alert('¡Registro Exitoso!');
                window.location.href = 'youserver.com/create-user.php';
            </script>
            ";
        } else {
            echo "
            <script>
                alert('¡Cuenta ya fue registrada!');
                window.location.href = 'youserver.com/create-user.php';
            </script>
            ";
        }
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
