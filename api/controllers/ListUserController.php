<?php
// Require the service
require_once __DIR__ . '/../services/UserListService.php';

// Controller class
class ListUserController {
    private $UserListService;

    // Create an instance of the user service
    public function __construct() {
        $this->UserListService = new UserListService();
    }

    // This method handles the HTTP request
    public function handleRequest() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $users = $this->UserListService->listUsers();
                echo json_encode(['success' => true, 'data' => $users]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Error fetching users',
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    }
}
?>