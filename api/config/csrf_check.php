<?php
// api/config/csrf_check.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. ESCUDO CSRF AUTOMÁTICO: Si la petición es POST, exigimos que venga el Token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $headers = getallheaders();
    
    // El token puede llegar en la cabecera de JS (X-CSRF-TOKEN) o en un input normal ($_POST)
    $clientToken = $headers['X-CSRF-TOKEN'] ?? $_POST['csrf_token'] ?? '';

    if (empty($clientToken) || !hash_equals($_SESSION['csrf_token'], $clientToken)) {
        http_response_code(403); // Acceso denegado / Prohibido
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Error de seguridad: Token CSRF inválido o vencido.'
        ]);
        exit; // Frena la ejecución del endpoint de inmediato
    }
}

// 2. FUNCIÓN MÁGICA CONTRA SESSION FIXATION (Para usar en tus controladores de login)
if (!function_exists('iniciar_sesion_segura')) {
    function iniciar_sesion_segura($user_id, $role_id) {
        // Rompe la llave vieja de sesión anónima y genera una nueva limpia para el usuario
        session_regenerate_id(true);
        
        // Guardamos los datos reales del usuario en la nueva sesión blindada
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role_id'] = $role_id;
        
        // Cambiamos también el token CSRF para el nuevo estado del usuario logueado
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}