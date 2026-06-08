<?php
require_once __DIR__ . '/../core/DBConfig.php';

class UserList { // Renamed the class so it does not conflict with Auth
    private $db;

    public function __construct() {
        $auth = new DBConfig();
        $this->db = $auth->getConnection();
    }

    public function getAllUsers() {
        $stmt = $this->db->query("
            SELECT id, username, email, role_id FROM users ORDER BY id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>