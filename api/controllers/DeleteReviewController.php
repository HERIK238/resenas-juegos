<?php
require_once __DIR__ . '/../services/DeleteReviewService.php';

class DeleteReviewController {
    private $service;

    public function __construct() {
        $this->service = new DeleteReviewService();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->sendResponse(['success' => false, 'message' => 'Method not allowed'], 405);
            return;
        }

        $this->deleteReview();
    }

    private function deleteReview() {
        if (empty($_SESSION['user_id'])) {
            $this->sendResponse(['success' => false, 'message' => 'Unauthorized'], 403);
            return;
        }

        $body = json_decode(file_get_contents('php://input'), true);
        $reviewId = intval($body['review_id'] ?? 0);

        if (!$reviewId) {
            $this->sendResponse(['success' => false, 'message' => 'Review ID is required'], 400);
            return;
        }

        try {
            $result = $this->service->deleteReview($_SESSION['user_id'], $reviewId);
            $this->sendResponse(['success' => true, 'message' => $result['message']]);
        } catch (Exception $e) {
            $this->sendResponse(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
