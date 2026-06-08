<?php
// Require the service
require_once __DIR__ . '/../services/LogoutService.php';

// Controller class
class LogoutController {
    private $logoutService;

    // Create an instance of the logout service
    public function __construct() {
        $this->logoutService = new LogoutService();
    }

    // This method handles the HTTP request
    public function handleRequest() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->logoutService->logout();
            if ($success) {
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Logout successful',
                ]);    
            } else {
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Could not log out',
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Method not allowed'
            ]);
        }
    }
}
?>