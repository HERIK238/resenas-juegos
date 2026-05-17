<?php
require_once __DIR__ . '/../core/DBConfig.php';

class User {
    private $db;

    public function __construct() {
        $dbConfig = new DBConfig();
        $this->db = $dbConfig->getConnection();
    }

    public function insertUser($data) {
        try {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El correo no tiene un formato válido");
            }
            $sqlCheckEmail = "SELECT COUNT(*) as count FROM users WHERE email = :email";
            $stmtCheckEmail = $this->db->prepare($sqlCheckEmail);
            $stmtCheckEmail->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmtCheckEmail->execute();
            
            if ($stmtCheckEmail->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
                throw new Exception("El correo ya está registrado.");
            }

            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

            // Insertamos solo lo que existe en la nueva DB
            $sql = "INSERT INTO users (username, email, password, role_id) VALUES (:username, :email, :password, :role_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $passwordHash, PDO::PARAM_STR);
            $stmt->bindParam(':role_id', $data['rol'], PDO::PARAM_INT);
            $stmt->execute();

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error al crear usuario: " . $e->getMessage());
        }
    }
}
?>