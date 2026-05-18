<?php
// Script para actualizar la base de datos y agregar el campo de foto de perfil
require_once './core/DBConfig.php';

try {
    $auth = new DBConfig();
    $db = $auth->getConnection();
    
    // Agregar columna profile_picture a la tabla users
    $sql = "ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL AFTER google_id";
    
    try {
        $db->exec($sql);
        echo json_encode(['status' => 'success', 'message' => 'Columna profile_picture agregada correctamente']);
    } catch (PDOException $e) {
        // Si la columna ya existe, no hay problema
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo json_encode(['status' => 'info', 'message' => 'La columna profile_picture ya existe']);
        } else {
            throw $e;
        }
    }
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
