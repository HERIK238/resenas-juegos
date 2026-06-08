<?php
// Require auth middleware
// require_once __DIR__ . '/middleware/auth.php';
require_once __DIR__ . '/../api/config/csrf_check.php';

// Verify that the user is authenticated and has role_id = 2
// Auth::checkAnyRole([1, 2, 3]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Require the controller to access its class
require_once __DIR__ . '/controllers/LogoutController.php';

// Create controller instance
$controller = new LogoutController();
// Call the method that handles the HTTP POST request and returns JSON
$controller->handleRequest();
?>