<?php
// dataModel.php

class DataModel {
    private $db;

    // Suponiendo que inyectas la conexión PDO en el constructor
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

        public function obtenerUsuariosConResenas() {
                // Ajuste: usar las tablas y columnas reales (users, reviews).
                $query = "SELECT
                                        u.id,
                                        u.username AS nombre,
                                        u.email,
                                        COUNT(r.id) AS total_reseñas
                                    FROM users u
                                    LEFT JOIN reviews r ON u.id = r.user_id
                                    GROUP BY u.id, u.username, u.email";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo de errores de base de datos
            error_log("Error en DataModel: " . $e->getMessage());
            return false;
        }
    }
}