<?php
// Requiere el servicio y el modelo
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../models/UserAuth.php';

// Clase controlador
class AuthController {
    private $authService;
    private $userModel;
    
    // Crea una instancia del servicio de usuario
    public function __construct() {
        $this->authService = new AuthService();
        $this->userModel = new User();
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            $this->sendResponse(['status' => 'error', 'message' => 'Método no permitido'], 405);
            return;
        }

        $input = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($input) || empty($password)) {
            $this->sendResponse(['status' => 'error', 'message' => 'Credenciales incompletas'], 400);
            return;
        }

        try {
            $result = $this->authService->authenticate($input, $password);
            $this->sendResponse($result);
        } catch (Exception $e) {
            $this->sendResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
?>