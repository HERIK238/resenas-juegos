<?php
require_once __DIR__ . '/../core/DBConfig.php';

class User {
    private $db;

    public function __construct() {
        $dbConfig = new DBConfig();
        $this->db = $dbConfig->getConnection();
    }
   
    public function findByCredentials($input) {
        try {
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $sql = "SELECT * FROM users WHERE email = :input";
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