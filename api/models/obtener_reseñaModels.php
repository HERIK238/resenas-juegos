<?php

require_once __DIR__ . '/../core/DBConfig.php';

class ObtenerReseñaModel {
    private $db;

    public function __construct() {
        $dbconfig = new DBConfig();
        $this -> db = $dbconfig -> getConnection();
    }

    public function fetchreseñas() {
        try {
            $sql = "SELECT r.*, u.username FROM reviews r 
            INNER JOIN users u ON r.user_id = u.id ";
            $stmt = $this -> db -> prepare($sql);
            $stmt -> execute();
            return $stmt -> fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            throw new Exception("error al obtener las reseñas:" . $e -> getMessage());
        }
    }
}

?>