<?php

header('Content-Type: application/json; charset=UTF-8');

// Do not display PHP errors in production
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

require_once __DIR__ . '/core/DBConfig.php';
require_once __DIR__ . '/../api/config/csrf_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

session_start();

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$generos_str = trim($_POST['generos_juego'] ?? ($_COOKIE['generos_juego'] ?? ''));

if (empty($email) || empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Required fields are missing.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'The email address is not valid.']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'The password must be at least 8 characters long.']);
    exit;
}

try {
    $dbConfig = new DBConfig();
    $db = $dbConfig->getConnection();

    $sql = 'SELECT id FROM users WHERE email = :email OR username = :username';
    $stmt = $db->prepare($sql);
    $stmt->execute([':email' => $email, ':username' => $username]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode(['status' => 'error', 'message' => 'The email or username is already in use.']);
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $insertSql = 'INSERT INTO users (email, username, password, role_id) VALUES (:email, :username, :password, 2)';
    $insertStmt = $db->prepare($insertSql);
    $insertStmt->execute([
        ':email' => $email,
        ':username' => $username,
        ':password' => $passwordHash
    ]);

    $user_id = $db->lastInsertId();

    if (!empty($generos_str)) {
        $generos_array = array_filter(array_map('trim', explode(',', $generos_str)));
        $sqlGetGenre = 'SELECT id FROM genres WHERE nombre = :nombre';
        $stmtGetGenre = $db->prepare($sqlGetGenre);
        $sqlInsertRelation = 'INSERT INTO user_genres (user_id, genre_id) VALUES (:user_id, :genre_id)';
        $stmtInsertRelation = $db->prepare($sqlInsertRelation);

        foreach ($generos_array as $nombre_genero) {
            if (empty($nombre_genero)) {
                continue;
            }
            $stmtGetGenre->execute([':nombre' => $nombre_genero]);
            $genero_db = $stmtGetGenre->fetch(PDO::FETCH_ASSOC);
            if ($genero_db) {
                $stmtInsertRelation->execute([
                    ':user_id' => $user_id,
                    ':genre_id' => $genero_db['id']
                ]);
            }
        }
    }

    setcookie('intereses_completados', '', time() - 3600, '/');
    setcookie('generos_juego', '', time() - 3600, '/');

    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['logged_in'] = true;

    echo json_encode(['status' => 'success', 'message' => 'Usuario registrado exitosamente.', 'user_id' => $user_id]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error.']);
}
