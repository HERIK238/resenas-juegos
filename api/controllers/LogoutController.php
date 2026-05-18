<?php
// Requiere el servicio
require_once __DIR__ . '/../services/LogoutService.php';

// Clase controlador
class LogoutController {
    private $logoutService;

    // Crea una instancia del servicio de logout
    public function __construct() {
        $this->logoutService = new LogoutService();
    }

    // Este método maneja la petición HTTP
    public function handleRequest() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->logoutService->logout();
            if ($success) {
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Logout exitoso',
                ]);    
            } else {
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'No se pudo cerrar la sesión',
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Método no permitido'
            ]);
        }
    }
}
?>