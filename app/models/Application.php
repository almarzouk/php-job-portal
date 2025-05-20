<?php
namespace Models;

use Core\Database;
use PDO;

class Application
{
    private $db;

    public function __construct()
    {
        $this->db = \Core\Database::connect();
    }

    public function getByJobId($jobId)
    {
        $sql = "SELECT * FROM applications WHERE job_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$jobId]);
        return $stmt->fetchAll();
    }
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM applications WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    public function updateStatus($applicationId, $newStatus)
    {
        $sql = "UPDATE applications SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$newStatus, $applicationId]);
    }
    public function getApplicationsByJobId($jobId)
    {
        $stmt = $this->db->prepare("
            SELECT a.*, u.name, u.email 
            FROM applications a
            JOIN users u ON a.user_id = u.id
            WHERE a.job_id = ?
            ORDER BY a.created_at DESC
        ");
        $stmt->execute([$jobId]);
        return $stmt->fetchAll();
    }
    public function getByUserId($userId)
{
    $stmt = $this->db->prepare("
        SELECT a.*, j.title AS job_title
        FROM applications a
        JOIN jobs j ON a.job_id = j.id
        WHERE a.user_id = ?
        ORDER BY a.created_at DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}


public function create($data)
{
    $sql = "INSERT INTO applications (user_id, job_id, name, email, file, message, created_at)
            VALUES (:user_id, :job_id, :name, :email, :file, :message, NOW())";

    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        ':user_id' => $data['user_id'],
        ':job_id'  => $data['job_id'],
        ':name'    => $data['name'],
        ':email'   => $data['email'],
        ':file'    => $data['file'],
        ':message' => $data['message'],
    ]);
}

// ✅ التحقق ما إذا كان المستخدم قد قدّم مسبقًا على الوظيفة
public function userHasAlreadyApplied($userId, $jobId)
{
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE user_id = ? AND job_id = ?");
    $stmt->execute([$userId, $jobId]);
    return $stmt->fetchColumn() > 0;
}
public function updateInterviewDetails($applicationId, $date, $link)
{
    $sql = "UPDATE applications SET interview_date = ?, interview_link = ? WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$date, $link, $applicationId]);
}
}