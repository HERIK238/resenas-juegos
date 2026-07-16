<?php
session_start();
header('Content-Type: application/json');
if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'logged' => true,
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'profile_picture' => $_SESSION['profile_picture'] ?? '',
        'role_id' => $_SESSION['role_id'] ?? ($_SESSION['rol'] ?? null)
    ]);
} else {
    echo json_encode(['logged' => false]);
}
?>