<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar los datos
    if (!isset($data['sites']) || !isset($data['emails'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit();
    }

    $sites = $data['sites'];
    $emails = $data['emails'];

    // Asegurarse de que todos los valores sean válidos
    $sites = array_map('trim', $sites);
    $emails = array_map('trim', $emails);

    // Convertir la lista de correos a una cadena separada por comas
    $emailString = implode(',', $emails);

    // Cargar configuración actual
    $config = json_decode(file_get_contents('config.json'), true);

    // Actualizar la configuración
    $config['sites'] = $sites;
    $config['emails'] = $emailString;

    // Guardar en config.json
    if (file_put_contents('config.json', json_encode($config, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true, 'message' => 'Cambios guardados con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar los cambios']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido']);
}
?>
