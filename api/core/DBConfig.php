<?php
class DBConfig {
    private $db;
    
    // Get the database connection
    public function getConnection() {
        if ($this->db) {
            return $this->db;
        }
        
        // Load the database credentials
        $config = require __DIR__ . '/../config/db.php';
        
        // Create the database connection string
        $dsn = "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
        
        try {
            // Create the database connection
            $this->db = new PDO($dsn, $config['user'], $config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->db;
        } catch (PDOException $e) {

            error_log("Database connection error: " . $e->getMessage());

            // Return a generic database error
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error. Please try again later.'
            ]);
            exit;
        }
    }
}
