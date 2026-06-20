<?php
require_once __DIR__ . '/../models/obtener_reseñaModels.php';

class ObtenerReseñaService {
    private $obtenerReseñaModel;

    public function __construct() {
        $this -> obtenerReseñaModel = new ObtenerReseñaModel();
    }

    public function obtenerReseña() {
        try {
            $reseñas = $this -> obtenerReseñaModel ->fetchreseñas();
            return ['status' => 'success', 'data' => $reseñas];

        }catch (Exception $e) {
            return ['status' => 'error', 'message' => $e -> getMessage()];
        }
    }
}

?>