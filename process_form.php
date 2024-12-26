<?php
// Cargar la configuración
$config = json_decode(file_get_contents('config.json'), true);
$sites = $config['sites'];
$emails = implode(',', $config['emails']); // Convertir el array de correos en una cadena separada por comas

// Configuración para mostrar errores (opcional)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $officerName = htmlspecialchars($_POST['officerName'], ENT_QUOTES, 'UTF-8');
    $shift = htmlspecialchars($_POST['shift'], ENT_QUOTES, 'UTF-8');
    $site = htmlspecialchars($_POST['site'], ENT_QUOTES, 'UTF-8');
    $incidentReport = htmlspecialchars($_POST['incidentReport'], ENT_QUOTES, 'UTF-8');
    $confirmSubmission = isset($_POST['confirmSubmission']);

    // Validar que el usuario ha confirmado el envío
    if (!$confirmSubmission) {
        echo "<script>alert('Por favor, confirme el envío del formulario.'); window.location.href='index.html';</script>";
        exit();
    }

    // Configurar los detalles del correo
    $to = $emails;
    $subject = 'Reporte ToTal System';
    $message = "
    <html>
    <head>
        <title>Total System Reporte</title>
        <style>
            body { 
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                background-color: #2c3e50; 
                color: #ffffff;  /* Texto en blanco */
                padding: 20px; 
            }
            .container {
                max-width: 600px;  /* Ancho máximo reducido */
                margin: 0 auto;  /* Centrar el contenido */
                background-color: #34495e;  /* Fondo oscuro para todo el contenido */
                padding: 20px;
                border-radius: 8px;
            }
            h2 { 
                color: #ffffff;  
                font-size: 26px;  
                text-align: center; 
                margin-bottom: 10px;
            }
            p { 
                font-size: 18px;  
                margin: 5px 0;
                padding: 5px 0;
            }
            .section { 
                margin-bottom: 15px;
                padding: 10px;
                border-radius: 5px;
            }
            .header { 
                font-weight: bold; 
                color: #ffffff;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                font-size: 12px;
                color: #95a5a6;
            }
            .textCont{
                font-weight: bold;
                color: #95b5a6;

                }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Detalles del Reporte</h2>
            <div class='section'>
                <p class='header'>Nombre:</p>
                <p class='textCont'>$officerName</p>
            </div>
            <div class='section'>
                <p class='header'>Turno:</p>
                <p class='textCont'>$shift</p>
            </div>
            <div class='section'>
                <p class='header'>Sitio:</p>
                <p class='textCont'>$site</p>
            </div>
            <div class='section'>
                <p class='header'>Reporte de Incidentes:</p>
                <p class='textCont'>$incidentReport</p>
            </div>
            <div class='footer'>
                <p>Este es un reporte enviado de Total System. Por favor, atienda este correo.</p>
            </div>
        </div>
    </body>
    </html>
    ";



    // Cabeceras para enviar un correo en formato HTML con archivos adjuntos
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "From: Reportes Total-System <report@ttscr.com>" . "\r\n";
    $headers .= "Bcc: $emails" . "\r\n";

    // Procesar archivos adjuntos si existen
    $boundary = md5(uniqid(time()));
    if (!empty($_FILES['formfile']['name'][0])) {
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"" . "\r\n";

        // Iniciar el mensaje con el cuerpo HTML
        $body = "--$boundary\r\n" .
            "Content-Type: text/html; charset=UTF-8\r\n" .
            "Content-Transfer-Encoding: 7bit\r\n\r\n" .
            $message . "\r\n";

        // Adjuntar archivos
        for ($i = 0; $i < count($_FILES['formfile']['name']); $i++) {
            if (is_uploaded_file($_FILES['formfile']['tmp_name'][$i])) {
                $fileSize = $_FILES['formfile']['size'][$i];
                if ($fileSize > 2097152) { // 2MB en bytes
                    echo "<script>alert('El archivo " . $_FILES['formfile']['name'][$i] . " excede el tamaño máximo permitido de 2MB.'); window.location.href='replog.php';</script>";
                    exit();
                }

                $fileName = $_FILES['formfile']['name'][$i];
                $fileType = $_FILES['formfile']['type'][$i];
                $fileContent = chunk_split(base64_encode(file_get_contents($_FILES['formfile']['tmp_name'][$i])));

                $body .= "--$boundary\r\n" .
                    "Content-Type: $fileType; name=\"$fileName\"\r\n" .
                    "Content-Transfer-Encoding: base64\r\n" .
                    "Content-Disposition: attachment; filename=\"$fileName\"\r\n\r\n" .
                    $fileContent . "\r\n";
            }
        }

        $body .= "--$boundary--";
    } else {
        $body = $message;
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    }

    // Enviar el correo
    if (mail($to, $subject, $body, $headers)) {
        // Redirigir a la página de confirmación si el correo se envía correctamente
        header("Location: confirmation.php");
        exit();
    } else {
        // Mostrar un error si el correo no se envía
        header("Location: failure.php");
    }
} else {
    echo "<script>alert('Método de solicitud no válido.'); window.location.href='replog.php';</script>";
}
