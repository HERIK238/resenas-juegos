<?php
// dataController.php
require_once __DIR__ . '/../services/dataServise.php';
require_once __DIR__ . '/../models/dataModel.php';

class DataController {
    private $service;

    public function __construct($dbConnection) {
        $this->service = new DataService($dbConnection);
    }

    public function listarUsuarios() {
        $resultado = $this->service->procesarDatosUsuarios();

        if (isset($resultado['error'])) {
            http_response_code(500);
            return json_encode([
                "status" => "error",
                "message" => $resultado['error']
            ]);
        }

        http_response_code(200);
        return json_encode([
            "status" => "success",
            "data" => $resultado
        ], JSON_UNESCAPED_UNICODE);
    }
}