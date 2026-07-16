<?php
// data_user.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once __DIR__ . '/core/DBConfig.php';
require_once __DIR__ . '/controllers/dataController.php';

try {
    $dbConnection = (new DBConfig())->getConnection();
    $controller = new DataController($dbConnection);
    echo $controller->listarUsuarios();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error de conexión: " . $e->getMessage()
    ]);
}
