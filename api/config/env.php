<?php
// =======================================================
// 1. GLOBAL SECURITY LAYER (Added at the top)
// =======================================================

// Disable error display to the public so .env data is not leaked
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Start a secure session if not already active for CSRF token usage
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}

// Create the secret token if it does not exist in the session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// =======================================================
// 2. YOUR ENVLOADER LOGIC (Remains intact)
// =======================================================
class EnvLoader {
    public static function load($path) {
        if (!file_exists($path)) {
            throw new Exception("El archivo .env no existe en: $path");
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Ignorar comentarios
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Separar clave y valor
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }
            
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            
            // Remover comillas si las hay
            $value = trim($value, '"\'');
            
            // Establecer la variable de entorno
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
    
    public static function get($key, $default = null) {
        return $_ENV[$key] ?? getenv($key) ?? $default;
    }
}