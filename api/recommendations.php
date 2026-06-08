<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/services/ReviewsService.php';
session_start();

$userId = $_SESSION['user_id'] ?? null;
$guestGenres = $_GET['genres'] ?? ($_COOKIE['generos_juego'] ?? null);
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

try {
    $service = new ReviewsService();
    $recommendations = $service->getRecommendations($userId, $guestGenres, $limit);
    echo json_encode(['success' => true, 'data' => $recommendations]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
