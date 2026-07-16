<?php
header("Content-Type: application/json; charset=UTF-8");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './core/DBConfig.php';
//require_once __DIR__ . '/../api/config/csrf_check.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verify POST method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Obtener el token del cuerpo de la solicitud
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['token']) || empty($input['token'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Token not provided']);
    exit;
}

$token = $input['token'];

try {

    if (empty($token)) {
        throw new Exception('Token not provided');
    }

    require_once __DIR__ . '/config/env.php';
    EnvLoader::load(__DIR__ . '/config/.env');
    $clientId = EnvLoader::get('GOOGLE_CLIENT_ID', '');

    if (empty($clientId)) {
        throw new Exception('Google client ID not configured');
    }

    $tokenInfoUrl = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($token);
    $response = file_get_contents($tokenInfoUrl);
    if ($response === false) {
        throw new Exception('No se pudo verificar el token con Google');
    }

    $payload = json_decode($response, true);
    if (!is_array($payload) || isset($payload['error_description'])) {
        throw new Exception('Token invalid according to Google');
    }

    if (!isset($payload['aud']) || $payload['aud'] !== $clientId) {
        throw new Exception('Invalid audience');
    }

    if (!in_array($payload['iss'], ['accounts.google.com', 'https://accounts.google.com'], true)) {
        throw new Exception('Invalid issuer');
    }

    if (!isset($payload['exp']) || $payload['exp'] < time()) {
        throw new Exception('Token expired');
    }

    // Ahora puedes usar $payload['email'], $payload['sub'], $payload['name'], $payload['picture']
    
    // Extraer datos del usuario
    $email = $payload['email'] ?? null;
    $name = $payload['name'] ?? null;
    $google_id = $payload['sub'] ?? null;
    $picture = $payload['picture'] ?? null; // URL de la foto de perfil de Google
    
    if (!$email || !$google_id) {
        throw new Exception('Incomplete data in Google token');
    }
    
    // Conectar a la base de datos
    $auth = new DBConfig();
    $db = $auth->getConnection();
    
    // Buscar si el usuario ya existe (por email o google_id)
    $sql = "SELECT * FROM users WHERE email = :email OR google_id = :google_id";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':email' => $email,
        ':google_id' => $google_id
    ]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // El usuario existe, actualizar google_id y foto si no los tiene
        if (!$user['google_id'] || !$user['profile_picture']) {
            $updateSql = "UPDATE users SET google_id = :google_id, profile_picture = :picture WHERE id = :id";
            $updateStmt = $db->prepare($updateSql);
            $updateStmt->execute([
                ':google_id' => $google_id,
                ':picture' => $picture,
                ':id' => $user['id']
            ]);
        }
        $user_id = $user['id'];
    } else {
        // Crear nuevo usuario
        // Generar un username basado en el email
        $username = explode('@', $email)[0];
        
        // Verify that the username is unique
        $checkSql = "SELECT id FROM users WHERE username = :username";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->execute([':username' => $username]);
        
        $counter = 1;
        $original_username = $username;
        while ($checkStmt->fetch()) {
            $username = $original_username . $counter;
            $checkStmt->execute([':username' => $username]);
            $counter++;
        }
        
        // Insertar nuevo usuario con foto de perfil
        $insertSql = "INSERT INTO users (email, username, google_id, profile_picture, role_id) VALUES (:email, :username, :google_id, :picture, 2)";
        $insertStmt = $db->prepare($insertSql);
        $insertStmt->execute([
            ':email' => $email,
            ':username' => $username,
            ':google_id' => $google_id,
            ':picture' => $picture
        ]);
        
        $user_id = $db->lastInsertId();
    }
    
    // Create a session for the user
    $_SESSION['user_id'] = $user_id;
    $_SESSION['email'] = $email;
    $_SESSION['username'] = $user['username'] ?? $username;
    $_SESSION['profile_picture'] = $picture;
    $_SESSION['logged_in'] = true;
    // Ensure role_id is present in session (use DB value if available, otherwise default to 2)
    $_SESSION['role_id'] = isset($user['role_id']) ? (int)$user['role_id'] : 2;
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Google login successful',
        'user_id' => $user_id,
        'profile_picture' => $picture,
        'username' => $user['username'] ?? $username
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error processing Google login: ' . $e->getMessage()
    ]);
}
?>
