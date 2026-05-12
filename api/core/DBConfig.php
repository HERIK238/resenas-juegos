<?php
class DBConfig {
    private $db;
    
    // Obtiene la conexión a la base de datos
    public function getConnection() {
        if ($this->db) {
            return $this->db;
        }
        
        // Carga los credenciales de la base de datos
        $config = require __DIR__ . '/../config/db.php';
        
        // Crea la cadena de conexión a la base de datos
        $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
        
        try {
            // Crea la conexión a la base de datos
            $this->db = new PDO($dsn, $config['user'], $config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->db;
        } catch (PDOException $e) {
            // Muestra el error de la base de datos
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error de conexión a la base de datos',
                'error'   => $e->getMessage()
            ]);
            exit;
        }
    }
}
