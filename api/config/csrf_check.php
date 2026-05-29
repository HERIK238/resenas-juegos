<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si la petición es POST, exigimos que venga el Token CSRF
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