<?php
// Requiere el conexion a la base de datos
require_once __DIR__ . '/../core/DBConfig.php';

// Clase UserAuth
class User {
    private $db;

    // Crea una nueva instancia
    public function __construct() {
        $dbConfig = new DBConfig();
        $this->db = $dbConfig->getConnection();
    }

    // Busca un usuario por credenciales    
    public function findByCredentials($input) {
        try {
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $sql = "SELECT * FROM users WHERE email = :input";
            } elseif (is_numeric($input)) {
                $sql = "SELECT * FROM users WHERE phone = :input";
            } else {
                $sql = "SELECT * FROM users WHERE username = :input";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':input', $input, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error al buscar usuario: " . $e->getMessage());
        }
    }
}
?>