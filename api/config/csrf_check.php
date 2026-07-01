<?php
// api/config/csrf_check.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. AUTOMATIC CSRF SHIELD: If the request is POST, the token must be present
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $headers = getallheaders();
    
    // The token can arrive in the JS header (X-CSRF-TOKEN) or in a normal input ($_POST)
    $clientToken = $headers['X-CSRF-TOKEN'] ?? $_POST['csrf_token'] ?? '';

    if (empty($clientToken) || !hash_equals($_SESSION['csrf_token'], $clientToken)) {
        http_response_code(403); // Access denied / Forbidden
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Security error: CSRF token invalid or expired.'
        ]);
        exit; // Stops endpoint execution immediately
    }
}

// 2. MAGIC FUNCTION AGAINST SESSION FIXATION (For use in login controllers)
if (!function_exists('iniciar_sesion_segura')) {
    function iniciar_sesion_segura($user_id, $role_id) {
        // Break the old anonymous session ID and generate a clean new one for the user
        session_regenerate_id(true);
        
        // Store the actual user data in the new secured session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role_id'] = $role_id;
        
        // Also change the CSRF token for the new logged-in user state
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}