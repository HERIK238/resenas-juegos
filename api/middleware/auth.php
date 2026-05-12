<?php

// Clase Auth del middleware
class Auth {
    private static $db;

    // Método para obtener la conexión a la base de datos
    private static function getDB() {
        if (!self::$db) {
            require_once __DIR__ . '/../core/DBConfig.php';
            $auth = new DBConfig();
            self::$db = $auth->getConnection();
        }
        return self::$db;
    }

    // Método para verificar si el usuario está autenticado
    public static function checkAuth() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'No autorizado']);
            exit();
        }
        return $_SESSION['user_id'];
    }

    // Método para verificar si el usuario tiene el rol requerido
    public static function checkRole($required_role_id) {
        $user_id = self::checkAuth();
        
        $db = self::getDB();
        $stmt = $db->prepare("SELECT role_id FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['role_id'] != $required_role_id) {
            http_response_code(403);
            echo json_encode(['error' => 'No tienes permisos']);
            exit;
        }
    }

    // Método para verificar si el usuario tiene al menos uno de los roles permitidos
    public static function checkAnyRole($allowed_roles) {
        $user_id = self::checkAuth();
        
        $db = self::getDB();
        $stmt = $db->prepare("SELECT role_id FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !in_array($user['role_id'], $allowed_roles)) {
            http_response_code(403);
            echo json_encode(['error' => 'No tienes permisos para acceder a esta función']);
            exit;
        }
    }
}
