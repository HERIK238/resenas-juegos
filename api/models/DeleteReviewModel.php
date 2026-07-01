<?php
require_once __DIR__ . '/../core/DBConfig.php';

class DeleteReviewModel {
    private $db;

    public function __construct() {
        $dbConfig = new DBConfig();
        $this->db = $dbConfig->getConnection();
    }

    public function deleteReview($reviewId, $userId) {
        $sql = "DELETE FROM reviews WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $reviewId, ':user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }
}
