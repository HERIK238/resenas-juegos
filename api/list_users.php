<?php
// Require auth middleware
require_once __DIR__ . '/middleware/auth.php';

// Verify that the user is authenticated and has role_id = 2
Auth::checkRole(2);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}
// Require the controller to access its class
require_once __DIR__ . '/controllers/ListUserController.php';

// Create controller instance
$controller = new ListUserController();
// Call the method that handles the HTTP request and returns JSON
$controller->handleRequest();
?>