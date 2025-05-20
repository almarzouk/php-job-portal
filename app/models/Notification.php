<?php
namespace Models;

use Core\Database;
use PDO;

class Notification
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($userId, $message)
    {
        $stmt = $this->db->prepare("INSERT INTO notifications (user_id, message, created_at) VALUES (?, ?, NOW())");
        return $stmt->execute([$userId, $message]);
    }

    public function getLatest($userId, $limit = 5)
    {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countUnread($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function markAllAsRead($userId)
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
    public function markAsRead($notificationId)
{
    $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
    return $stmt->execute([$notificationId]);
}
}