<?php
//requiere controlador para acceder a su clase
require_once __DIR__ . '/../api/controllers/obtener_reseñaController.php';

$controller = new ObtenerReseñaController();
$controller -> obtenerReseña();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}

?>