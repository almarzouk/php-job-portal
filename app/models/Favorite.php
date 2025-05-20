<?php
namespace Models;

use Core\Database;

class Favorite
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function toggle($userId, $jobId)
    {
        $stmt = $this->db->prepare("SELECT * FROM favorites WHERE user_id = ? AND job_id = ?");
        $stmt->execute([$userId, $jobId]);

        if ($stmt->fetch()) {
            $delete = $this->db->prepare("DELETE FROM favorites WHERE user_id = ? AND job_id = ?");
            return $delete->execute([$userId, $jobId]);
        } else {
            $insert = $this->db->prepare("INSERT INTO favorites (user_id, job_id) VALUES (?, ?)");
            return $insert->execute([$userId, $jobId]);
        }
    }

    public function getUserFavorites($userId)
    {
        $stmt = $this->db->prepare("
            SELECT j.* FROM favorites f
            JOIN jobs j ON f.job_id = j.id
            WHERE f.user_id = ?
            ORDER BY f.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function isFavorite($userId, $jobId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ? AND job_id = ?");
        $stmt->execute([$userId, $jobId]);
        return $stmt->fetchColumn() > 0;
    }
    public function getFavoriteJobIdsByUser($userId)
{
    $stmt = $this->db->prepare("SELECT job_id FROM favorites WHERE user_id = ?");
    $stmt->execute([$userId]);
    return array_column($stmt->fetchAll(), 'job_id');
}
}