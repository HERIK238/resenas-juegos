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
                    'success' => true, 
                    'message' => 'Sesión cerrada correctamente',
                ]);    
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo cerrar la sesión']);
                echo json_encode([
                    'success' => false, 
                    'message' => 'No se pudo cerrar la sesión',
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }
}
?>