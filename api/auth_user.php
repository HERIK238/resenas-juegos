<?php
// Requiere el controllador para acceder a su clase


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once __DIR__ . '/../api/config/csrf_check.php';
require_once __DIR__ . '/../api/controllers/AuthController.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

// Accept POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
    exit;
}

// Create controller instance
$authController = new AuthController();
// Call the method that handles the HTTP POST request and returns JSON
$authController->login();
?>