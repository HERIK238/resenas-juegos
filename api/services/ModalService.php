<?php
// Requiere el modelo
require_once __DIR__ . '/../models/ModalAuth.php';

// Clase UserService
class UserService {
    private $userModel;

    public function __construct(User $userModel) {
        $this->userModel = $userModel;
    }

    // Crea un nuevo usuario
    public function createUser($username, $email, $status, $password, $role, $documento) {
        // Encripta la contraseña
        $hash = password_hash($password, PASSWORD_BCRYPT);

        // Llama al modelo para guardar
        $nuevoId = $this->userModel->insertUser([
            'username' => $username,
            'documento' => $documento,
            'email'    => $email,
            'estado'   => $status,
            'password' => $hash,
            'rol'      => $role
        ]);

        if (!$nuevoId) {
            return [
                'status'  => 'error',
                'message' => 'No se pudo crear el usuario'
            ];
        }

        return [
            'status'  => 'success',
            'message' => 'Usuario creado correctamente',
            'user_id' => $nuevoId
        ];
    }
}
?>
