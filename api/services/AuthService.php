<?php
// Requiere el modelo
require_once __DIR__ . '/../models/UserAuth.php';

// Clase AuthService
class AuthService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Autentica un usuario
    public function authenticate($input, $password) {
        $user = $this->userModel->findByCredentials($input);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return [
                'status' => 'error',
                'message' => 'Credenciales incorrectas'
            ];
        }

        // Iniciar sesión
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['rol'] = $user['rol'];

        return [
            'status' => 'success',
            'message' => 'Login exitoso',
            'user_data' => [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email']
            ]
        ];
    }
}
?>
