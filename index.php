

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Login</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (opcional, si usas íconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Enlace al archivo CSS del proyecto de bitácora -->
    <link rel="stylesheet" href="">

    <!-- Estilos específicos para el formulario de login -->
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

        /* Estilo del contenedor de login */
        .login-container {
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

        /* Botón de enviar */
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

        /* Ajustes responsivos */
        @media (max-width: 768px) {
            .login-container {
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
                <div class="login-container mx-auto">
                    <!-- Encabezado con logo y título -->
                    <div class="header-container mb-4">
                        <img src="image/TotalSystem.png" alt="Logo" class="logo">
                        <h1 class="header-title">Reportes</h1>
                    </div>

                    <!-- Mensaje de bienvenida -->
                    <h2>Hola de nuevo!</h2>
                    <p>Ingresa tus credenciales</p>

                    <!-- Formulario de inicio de sesión -->
                    <form action="./endpoint/login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Acceder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS y dependencias (Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script opcional para mostrar formularios adicionales (registro, etc.) -->
    
</body>

</html>
