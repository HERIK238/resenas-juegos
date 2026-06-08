<?php
require_once __DIR__ . '/../core/DBConfig.php';

class ReviewModel {
    private $db;

    public function __construct() {
        $dbConfig = new DBConfig();
        $this->db = $dbConfig->getConnection();
    }

    public function getReviewsByUser($userId) {
        $sql = "SELECT r.id, r.titulo AS review_title, r.contenido AS review_content, r.calificacion, r.created_at, g.id AS game_id, g.titulo AS game_title, g.descripcion AS game_description, g.portada_url AS game_cover_url
                FROM reviews r
                LEFT JOIN games g ON g.id = r.game_id
                WHERE r.user_id = :user_id
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createReview($userId, $gameId, $title, $content, $rating = null) {
        $sql = "INSERT INTO reviews (user_id, game_id, titulo, contenido, calificacion) VALUES (:user_id, :game_id, :titulo, :contenido, :calificacion)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':game_id' => $gameId,
            ':titulo' => $title,
            ':contenido' => $content,
            ':calificacion' => $rating
        ]);
        return $this->db->lastInsertId();
    }

    public function getGameById($gameId) {
        $sql = "SELECT id, titulo, descripcion, portada_url, created_at FROM games WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $gameId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrCreateGameByName($gameName) {
        // Search for existing game (case-insensitive)
        $sql = "SELECT id, titulo, descripcion, portada_url, created_at FROM games WHERE LOWER(titulo) = LOWER(:titulo)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':titulo' => $gameName]);
        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($game) {
            return $game;
        }

        // Game doesn't exist, create it
        $createSql = "INSERT INTO games (titulo, descripcion, portada_url, created_at) VALUES (:titulo, :descripcion, :portada_url, NOW())";
        $createStmt = $this->db->prepare($createSql);
        $createStmt->execute([
            ':titulo' => $gameName,
            ':descripcion' => '',
            ':portada_url' => null
        ]);

        $gameId = $this->db->lastInsertId();
        return $this->getGameById($gameId);
    }

    public function getAllGames($search = null) {
        $sql = "SELECT id, titulo, descripcion, portada_url, created_at FROM games";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE titulo LIKE :search OR descripcion LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllGenres() {
        $sql = "SELECT id, nombre FROM genres ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserPreferredGenres($userId) {
        $sql = "SELECT g.nombre FROM genres g
                INNER JOIN user_genres ug ON ug.genre_id = g.id
                WHERE ug.user_id = :user_id
                ORDER BY g.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'nombre');
    }

    public function getTopRatedGames($limit = 10) {
        $sql = "SELECT g.id, g.titulo, g.descripcion, g.portada_url, g.created_at,
                       AVG(r.calificacion) AS average_rating,
                       COUNT(r.id) AS review_count
                FROM games g
                LEFT JOIN reviews r ON r.game_id = g.id
                GROUP BY g.id
                ORDER BY average_rating DESC, review_count DESC, g.created_at DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
