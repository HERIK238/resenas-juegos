<?php
// Requiere el servicio y el modelo
require_once __DIR__ . '/../services/ModalService.php';
require_once __DIR__ . '/../models/ModalAuth.php';

// Clase controlador
class ModalController {
    private $userService;
    private $userModel;
    
    // Crea una instancia del servicio de usuario
    public function __construct() {
        $this->userModel   = new User();
        $this->userService = new UserService($this->userModel);
    }

    public function create() {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            $this->sendResponse(['status' => 'error', 'message' => 'Método no permitido'], 405);
            return;
        }

        // Aquí se reciben los datos enviados por el modal
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
            $this->sendResponse(['status' => 'error', 'message' => 'Datos incompletos'], 400);
            return;
        }

        try {
            // Llama al servicio para crear el usuario
            $result = $this->userService->createUser($username, $email, $status, $password, $role, $documento);
            $this->sendResponse($result);
        } catch (Exception $e) {
            // Cambiamos el código de respuesta a 200 para que el callback 'success' de AJAX
            // pueda procesar el mensaje de error de validación.
            // Los errores de validación (como "email ya existe") no son errores del servidor.
            $this->sendResponse(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }

    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
