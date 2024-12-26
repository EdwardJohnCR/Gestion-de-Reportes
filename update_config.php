<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data) {
        $config = array(
            // 'bitacora' => $data['bitacora'],
            'sites' => $data['sites'],
            'emails' => $data['emails'],
        );
        
        if (file_put_contents('config.json', json_encode($config, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar el archivo.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos no válidos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);
}
?>
