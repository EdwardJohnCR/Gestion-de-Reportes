<?php
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT `password`, `role`, `tbl_user_id` FROM `tbl_user` WHERE `username` = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $stored_password = $row['password'];  // Hash almacenado
        $stored_role = $row['role'];
        $user_id = $row['tbl_user_id'];

        // Usar password_verify para comparar la contraseña ingresada con el hash almacenado
        if (password_verify($password, $stored_password)) {
            session_start();
            $_SESSION['user_id'] = $user_id;

            if ($stored_role == 'admin') {
                echo "
                <script>
                    window.location.href = 'youserver.com/lobby.php';
                </script>
                ";
            } else if ($stored_role == 'user') {
                echo "
                <script>
                    window.location.href = 'youserver.com/replog.php';
                </script>
                ";
            }
        } else {
            echo "
            <script>
                window.location.href = 'youserver.com/error_pass.php';
            </script>
            ";
        }
    } else {
        echo "
            <script>
                window.location.href = 'youserver.com/error_user.php';
            </script>
        ";
    }
}
?>
