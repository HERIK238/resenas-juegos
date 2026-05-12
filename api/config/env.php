<?php
// Logica env loader (cargador de env vanilla)
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