<?php
// Requiere la conexión a la base de datos
require_once __DIR__ . '/../core/DBConfig.php';

// Clase UserList
class User {
    private $db;

    // Crea una nueva instancia
    public function __construct() {
        $auth = new DBConfig();
        $this->db = $auth->getConnection();
    }

    // Obtiene todos los usuarios
    public function getAllUsers() {
        $stmt = $this->db->query("
            SELECT 
                user_id,
                username,
                email
            FROM users
            ORDER BY user_id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
