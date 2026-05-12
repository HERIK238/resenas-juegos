<?php
// Requiere el middleware de auth
// require_once __DIR__ . '/middleware/auth.php';

// Verifica que el usuario esté autenticado y tenga role_id = 2
// Auth::checkAnyRole([1, 2, 3]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Requiere el controllador para acceder a su clase
require_once __DIR__ . '/controllers/LogoutController.php';

// Crea una instancia del controlador
$controller = new LogoutController();
// Llama al método que maneja la petición HTTP (POST) y responde en JSON
$controller->handleRequest();
?>