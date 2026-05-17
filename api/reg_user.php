<?php
// Siempre devolver JSON
header("Content-Type: application/json; charset=UTF-8");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir archivo de conexión a la base de datos
require_once './core/DBConfig.php';

// Crear variable de sesión
session_start();

// Validar solo los campos que el formulario envía
$required_fields = ['email', 'username', 'password'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => "Falta el campo requerido: $field"]);
        exit;
    }
}

// Obtener y sanitizar datos
$data = [
    'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
    'username' => htmlspecialchars($_POST['username']),
    'password' => $_POST['password']
];

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
            'message' => 'El correo o el usuario ya están en uso.'
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
    
    // =================================================================
    // LÓGICA DE BURBUJAS: GUARDAR LOS GÉNEROS DE JUEGOS
    // =================================================================
    $generos_str = $_POST['generos_juego'] ?? $_COOKIE['generos_juego'] ?? '';
    
    if (!empty($generos_str)) {
        $generos_array = explode(',', $generos_str);
        
        $sqlGetGenre = "SELECT id FROM genres WHERE nombre = :nombre";
        $stmtGetGenre = $db->prepare($sqlGetGenre);
        
        $sqlInsertRelation = "INSERT INTO user_genres (user_id, genre_id) VALUES (:user_id, :genre_id)";
        $stmtInsertRelation = $db->prepare($sqlInsertRelation);

        foreach ($generos_array as $nombre_genero) {
            $nombre_genero = trim($nombre_genero);
            $stmtGetGenre->execute([':nombre' => $nombre_genero]);
            $genero_db = $stmtGetGenre->fetch(PDO::FETCH_ASSOC);

            if ($genero_db) {
                $stmtInsertRelation->execute([
                    ':user_id' => $user_id,
                    ':genre_id' => $genero_db['id']
                ]);
            }
        }
        
        // Limpiamos las cookies para que no le vuelva a salir la pantalla de inicio
        setcookie('intereses_completados', '', time() - 3600, '/');
        setcookie('generos_juego', '', time() - 3600, '/');
    }
    // =================================================================
    
    // Crear sesión para el usuario recién registrado
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $data['username'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['logged_in'] = true;
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Usuario registrado exitosamente.',
        'user_id' => $user_id
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
}
?>