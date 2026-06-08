<?php
require_once __DIR__ . '/../services/ReviewsService.php';

class ReviewsController {
    private $service;

    public function __construct() {
        $this->service = new ReviewsService();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->listReviews();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createReview();
            return;
        }

        $this->sendResponse(['success' => false, 'message' => 'Method not allowed'], 405);
    }

    private function listReviews() {
        session_start();
        if (empty($_SESSION['user_id'])) {
            $this->sendResponse(['success' => false, 'message' => 'No autorizado'], 403);
            return;
        }

        $reviews = $this->service->getUserReviews($_SESSION['user_id']);
        $this->sendResponse(['success' => true, 'data' => $reviews]);
    }

    private function createReview() {
        session_start();
        if (empty($_SESSION['user_id'])) {
            $this->sendResponse(['success' => false, 'message' => 'No autorizado'], 403);
            return;
        }

        $gameId = $_POST['game_id'] ?? null;
        $title = trim($_POST['titulo'] ?? '');
        $content = trim($_POST['contenido'] ?? '');
        $rating = isset($_POST['calificacion']) && $_POST['calificacion'] !== '' ? $_POST['calificacion'] : null;

        if (!$gameId) {
            $this->sendResponse(['success' => false, 'message' => 'Game ID is required'], 400);
            return;
        }

        try {
            $result = $this->service->saveReview($_SESSION['user_id'], $gameId, $title, $content, $rating);
            $this->sendResponse(['success' => true, 'message' => $result['message']]);
        } catch (Exception $e) {
            $this->sendResponse(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
