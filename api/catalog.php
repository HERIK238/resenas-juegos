<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/services/ReviewsService.php';
session_start();

$search = isset($_GET['search']) ? trim($_GET['search']) : null;
$userId = $_SESSION['user_id'] ?? null;

try {
    $service = new ReviewsService();
    $catalog = $service->getCatalog($search);
    $preferredGenres = [];
    if ($userId) {
        $preferredGenres = $service->getPreferredGenres($userId);
    }

    echo json_encode([
        'success' => true,
        'data' => $catalog,
        'preferred_genres' => $preferredGenres
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
