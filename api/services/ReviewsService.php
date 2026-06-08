<?php
require_once __DIR__ . '/../models/ReviewsModels.php';

class ReviewsService {
    private $model;

    public function __construct() {
        $this->model = new ReviewModel();
    }

    public function getUserReviews($userId) {
        return $this->model->getReviewsByUser($userId);
    }

    public function saveReview($userId, $gameId, $title, $content, $rating = null) {
        $game = $this->model->getGameById($gameId);
        if (!$game) {
            throw new Exception('Game not found');
        }

        if (empty($title) || empty($content)) {
            throw new Exception('Review title and content are required');
        }

        if ($rating !== null && (!is_numeric($rating) || $rating < 0 || $rating > 10)) {
            throw new Exception('Rating must be between 0 and 10');
        }

        $this->model->createReview($userId, $gameId, $title, $content, $rating);
        return ['message' => 'Review created successfully'];
    }

    public function getCatalog($search = null) {
        return [
            'games' => $this->model->getAllGames($search),
            'genres' => $this->model->getAllGenres()
        ];
    }

    public function getPreferredGenres($userId) {
        return $this->model->getUserPreferredGenres($userId);
    }

    public function getRecommendations($userId = null, $guestGenres = null, $limit = 10) {
        $preferredGenres = [];

        if ($userId) {
            $preferredGenres = $this->model->getUserPreferredGenres($userId);
        }

        if (empty($preferredGenres) && !empty($guestGenres)) {
            $preferredGenres = array_filter(array_map('trim', explode(',', $guestGenres)));
        }

        $games = $this->model->getAllGames();

        if (empty($preferredGenres)) {
            return $this->model->getTopRatedGames($limit);
        }

        $preferredGenres = array_map('mb_strtolower', $preferredGenres);
        $scored = [];

        foreach ($games as $game) {
            $score = $this->calculateGameScore($game, $preferredGenres);
            if ($score > 0) {
                $game['recommendation_score'] = $score;
                $scored[] = $game;
            }
        }

        usort($scored, function ($a, $b) {
            return $b['recommendation_score'] <=> $a['recommendation_score'];
        });

        if (count($scored) === 0) {
            return $this->model->getTopRatedGames($limit);
        }

        return array_slice($scored, 0, $limit);
    }

    private function calculateGameScore(array $game, array $preferredGenres) {
        $title = mb_strtolower($game['titulo']);
        $description = mb_strtolower($game['descripcion'] ?? '');
        $score = 0;

        foreach ($preferredGenres as $genre) {
            if (empty($genre)) {
                continue;
            }

            if (mb_stripos($title, $genre) !== false) {
                $score += 2;
            }
            if (mb_stripos($description, $genre) !== false) {
                $score += 1;
            }
        }

        return $score;
    }
}
