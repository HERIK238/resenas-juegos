<?php
require_once __DIR__ . '/../models/UserAuth.php';

class AuthService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function authenticate($input, $password) {
        $user = $this->userModel->findByCredentials($input);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return ['status' => 'error', 'message' => 'Credenciales incorrectas'];
        }

        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        // Normalize session key for role
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['profile_picture'] = $user['profile_picture'] ?? '';

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