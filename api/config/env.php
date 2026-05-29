<?php
// =======================================================
// 1. CAPA DE SEGURIDAD GLOBAL (Agregado arriba del todo)
// =======================================================

// Desactivar visualización de errores al público para no filtrar datos del .env
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión segura si no está activa para el Token CSRF
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}

// Crear el token secreto si no existe en la sesión
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// =======================================================
// 2. TU LÓGICA DE ENVLOADER (Se mantiene intacta)
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