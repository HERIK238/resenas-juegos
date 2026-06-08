<?php
// Require the service and model
require_once __DIR__ . '/../services/ModalService.php';
require_once __DIR__ . '/../models/ModalAuth.php';

// Controller class
class ModalController {
    private $userService;
    private $userModel;
    
    // Create an instance of the user service
    public function __construct() {
        $this->userModel   = new User();
        $this->userService = new UserService($this->userModel);
    }

    public function create() {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            $this->sendResponse(['status' => 'error', 'message' => 'Method not allowed'], 405);
            return;
        }

        // Here we receive the data sent from the modal
        $username = $_POST['username'] ?? '';
        $documento = $_POST['documento'] ?? '';
        $email    = $_POST['email'] ?? '';
        $status   = $_POST['estado'] ?? '';
        $password = $_POST['password'] ?? '';
        $role     = $_POST['rol'] ?? '';

        if (
            empty($username) ||
            empty($email) ||
            empty($status) ||
            empty($password) ||
            empty($role) ||
            empty($documento)
        ) {
            $this->sendResponse(['status' => 'error', 'message' => 'Incomplete data'], 400);
            return;
        }

        try {
            // Llama al servicio para crear el usuario
            $result = $this->userService->createUser($username, $email, $status, $password, $role, $documento);
            $this->sendResponse($result);
        } catch (Exception $e) {
            // Change the response code to 200 so the AJAX 'success' callback
            // can process the validation error message.
            // Validation errors (like "email already exists") are not server errors.
            $this->sendResponse(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
