<?php
require_once __DIR__ . '/../models/DeleteReviewModel.php';

class DeleteReviewService {
    private $model;

    public function __construct() {
        $this->model = new DeleteReviewModel();
    }

    public function deleteReview($userId, $reviewId) {
        $deleted = $this->model->deleteReview($reviewId, $userId);
        if (!$deleted) {
            throw new Exception('Review not found or you do not have permission to delete it');
        }
        return ['message' => 'Review deleted successfully'];
    }
}
