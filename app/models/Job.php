<?php
namespace Models;

use Core\Database;
use PDO;
class Job
{
    private $db;

    public function __construct()
    {
        $this->db = \Core\Database::connect();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM jobs ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // داخل نموذج Job
public function createJob($title, $location, $category, $description)
{
    try {
        $sql = "INSERT INTO jobs (title, location, category, description, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$title, $location, $category, $description]);
        return true;
    } catch (\PDOException $e) {
        return false;
    }
}
public function deleteById($id)
{
    $sql = "DELETE FROM jobs WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$id]);
}
public function findById($id)
{
    $stmt = $this->db->prepare("SELECT * FROM jobs WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

public function updateJob($id, $title, $location, $category, $description)
{
    $stmt = $this->db->prepare("UPDATE jobs SET title = ?, location = ?, category = ?, description = ? WHERE id = ?");
    return $stmt->execute([$title, $location, $category, $description, $id]);
}
// models/Job.php
public function getJobsWithApplicationCounts()
{
    $sql = "SELECT j.id, j.title, j.is_visible, COUNT(a.id) AS total_applications
            FROM jobs j
            LEFT JOIN applications a ON j.id = a.job_id
            GROUP BY j.id
            ORDER BY j.created_at DESC";
    return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
}
public function updateVisibility($id, $visible)
{
    $stmt = $this->db->prepare("UPDATE jobs SET is_visible = ? WHERE id = ?");
    return $stmt->execute([$visible, $id]);
}
public function search($query)
{
    $sql = "SELECT * FROM jobs 
            WHERE title LIKE :q OR location LIKE :q OR category LIKE :q 
            ORDER BY created_at DESC";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([':q' => '%' . $query . '%']);
    return $stmt->fetchAll();
}
}