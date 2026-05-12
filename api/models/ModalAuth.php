<?php
// Requiere la conexión a la base de datos
require_once __DIR__ . '/../core/DBConfig.php';

class User {
    private $db;

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

    // Inserta un nuevo usuario en la base de datos
    public function insertUser($data) {
        try {
            // Validar correo
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("El correo no tiene un formato válido.");
            }

            // Verificar si documento ya existe
            $sqlCheckDocument = "SELECT COUNT(*) as count FROM users WHERE documento = :documento";
            $stmtCheckDocument = $this->db->prepare($sqlCheckDocument);
            $stmtCheckDocument->bindParam(':documento', $data['documento'], PDO::PARAM_STR);
            $stmtCheckDocument->execute();
            if ($stmtCheckDocument->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
                throw new Exception("El documento ya existe en el sistema.");
            }

            // Verificar si correo ya existe
            $sqlCheckEmail = "SELECT COUNT(*) as count FROM users WHERE email = :email";
            $stmtCheckEmail = $this->db->prepare($sqlCheckEmail);
            $stmtCheckEmail->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmtCheckEmail->execute();
            if ($stmtCheckEmail->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
                throw new Exception("El correo ya está registrado.");
            }

            // Encriptar contraseña
            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

            // Insertar usuario
            $sql = "INSERT INTO users 
                    (username, email, estado, password, rol, documento)
                    VALUES (:username, :email, :estado, :password, :rol, :documento)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindParam(':documento', $data['documento'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $passwordHash, PDO::PARAM_STR);
            $stmt->bindParam(':rol', $data['rol'], PDO::PARAM_STR);

            $stmt->execute();

            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            throw new Exception("Error al insertar usuario: " . $e->getMessage());
        }
    }
}
?>
