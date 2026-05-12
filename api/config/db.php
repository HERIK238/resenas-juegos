<?php

require_once __DIR__ . '/env.php';

// Cargar el archivo .env
EnvLoader::load(__DIR__ . '/.env');

// Mapear las variables de NuevoProyecto a la estructura de Survival
return [
    'driver'   => EnvLoader::get('DB_CONNECTION', 'mysql'),
    'host'     => EnvLoader::get('DB_HOST', 'localhost'),
    'dbname'   => EnvLoader::get('DB_DATABASE', EnvLoader::get('DB_NAME')), // acepta DB_NAME de NuevoProyecto
    'port'     => EnvLoader::get('DB_PORT', 3306),
    'user'     => EnvLoader::get('DB_USERNAME', EnvLoader::get('DB_USER')), // acepta DB_USER de NuevoProyecto
    'password' => EnvLoader::get('DB_PASSWORD', EnvLoader::get('DB_PASS')), // acepta DB_PASS de NuevoProyecto
    'charset'  => EnvLoader::get('DB_CHARSET', 'utf8mb4')
];
