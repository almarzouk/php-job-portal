<?php
namespace Models;

use Core\Database;
use PDO;

class Message
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getMessagesBetween($user1, $user2)
    {
        $sql = "
            SELECT * FROM messages 
            WHERE (sender_id = :user1 AND receiver_id = :user2)
               OR (sender_id = :user2 AND receiver_id = :user1)
            ORDER BY created_at ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user1' => $user1, 'user2' => $user2]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sendMessage($from, $to, $text)
    {
        $stmt = $this->db->prepare("INSERT INTO messages (sender_id, receiver_id, message, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$from, $to, $text]);
    }

    public function markMessagesAsRead($from, $to)
    {
        $sql = "UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ? AND is_read = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$from, $to]);
    }

    public function getChatUsers($userId)
    {
        $sql = "
            SELECT u.id, u.name, u.email,
                MAX(m.created_at) as last_message_time,
                SUM(CASE WHEN m.receiver_id = :me AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count
            FROM messages m
            JOIN users u ON (u.id = IF(m.sender_id = :me, m.receiver_id, m.sender_id))
            WHERE m.sender_id = :me OR m.receiver_id = :me
            GROUP BY u.id, u.name, u.email
            ORDER BY last_message_time DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['me' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 