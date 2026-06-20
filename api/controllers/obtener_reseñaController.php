<?php
// Require the service and model
require_once __DIR__ . '/../services/obtener_reseñaService.php';
require_once __DIR__ . '/../models/obtener_reseñaModels.php';

//controller class
class ObtenerReseñaController {
    
    private $obtenerReseñaService;
    private $obtenrReseñaModel;

    // Crea una instancia del servicio de usuario
    public function __construct() {

        $this -> obtenerReseñaService = new ObtenerReseñaService();
        $this -> obtenrReseñaModel = new ObtenerReseñaModel();

    }

    public function obtenerReseña() {

        if ($_SERVER["REQUEST_METHOD"] !== "GET") {
            $this -> sendResponse(['status' => 'error', 'message' => 'Method not allowed'], 405);
            return;
        }

        try {
            $result = $this -> obtenerReseñaService -> obtenerReseña();
            $this -> sendResponse($result);
        }catch (Exception $e) {
            $this -> sendResponse(['status' => 'error', 'messsage' => $e -> getMessage()], 500);
        }
    }

    private function sendResponse($data, $statusCode = 200){
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

?>