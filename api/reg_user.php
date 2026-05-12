<?php
// Siempre devolver JSON
header("Content-Type: application/json; charset=UTF-8");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

// Incluir archivo de conexión a la base de datos
require_once './core/DBConfig.php';

// Crear variable de sesión
session_start();

// Log para verificar el método
/* file_put_contents("debug_log.txt", "Método: " . $_SERVER["REQUEST_METHOD"] . "\n", FILE_APPEND); */

/* // Verificar método POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
} */

// Validar solo los campos que el formulario envía
$required_fields = ['email', 'username', 'password'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Missing required field: $field"]);
        exit;
    }
}

// Obtener y sanitizar datos
$data = [
    'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
    'username' => filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
    'password' => $_POST['password']
];

// Validar email
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
    exit;
}

// Encriptar contraseña
$hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);

try {
    // Crear conexión a la base de datos
    $auth = new DBConfig();
    $db = $auth->getConnection();
    
    // Verificar si el email o username ya existen
    $sql = "SELECT * FROM users WHERE email = :email OR username = :username";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':email' => $data['email'],
        ':username' => $data['username']
    ]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email or username already in use.'
        ]);
        exit;
    }
    
    // Insertar nuevo usuario
    $sql = "INSERT INTO users (email, username, password) VALUES (:email, :username, :password)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':email' => $data['email'],
        ':username' => $data['username'],
        ':password' => $hashed_password
    ]);
    
    $user_id = $db->lastInsertId();
    
    // Crear sesión
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $data['username'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['logged_in'] = true;
    
    echo json_encode([
        'status' => 'success',
        'message' => 'User registered successfully.',
        'user_id' => $user_id
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
