<?php

namespace Models;

use Core\Database;
use PDO;
class User
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = \Core\Database::connect();
        } catch (\PDOException $e) {
            die("❌ Verbindung zur Datenbank fehlgeschlagen: " . $e->getMessage());
        }
    }

    // تسجيل مستخدم جديد
    public function register($name, $email, $password)
    {
        // تحقق من وجود البريد مسبقًا
        if ($this->findByEmail($email)) {
            return '❌ Diese E-Mail-Adresse ist bereits registriert.';
        }

        try {
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$name, $email, $password]);

            return true;
        } catch (\PDOException $e) {
            return '❌ Ein Fehler ist aufgetreten: ' . $e->getMessage();
        }
    }

    // التحقق من وجود المستخدم بالبريد الإلكتروني
    public function findByEmail($email)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false; // لا نعيد رسالة خطأ هنا، فقط نُعالج بهدوء
        }
    }

    // التحقق من بيانات تسجيل الدخول
    public function login($email, $password)
    {
        try {
            $user = $this->findByEmail($email);

            if (is_array($user) && password_verify($password, $user['password'])) {
                return $user;
            } else {
                return '❌ Falsche E-Mail oder Passwort.';
            }
        } catch (\PDOException $e) {
            return '❌ Fehler beim Login: ' . $e->getMessage();
        }
    }

    // جلب بيانات مستخدم بواسطة ID
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return false;
        }
    }
    public function update($id, $data)
    {
        $sql = "UPDATE users SET name = :name, email = :email";
        
        if (isset($data['password'])) {
            $sql .= ", password = :password";
        }
    
        if (isset($data['cv'])) {
            $sql .= ", cv = :cv";
        }
    
        $sql .= " WHERE id = :id";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $data['name']);
        $stmt->bindValue(':email', $data['email']);
        
        if (isset($data['password'])) {
            $stmt->bindValue(':password', $data['password']);
        }
    
        if (isset($data['cv'])) {
            $stmt->bindValue(':cv', $data['cv']);
        }
    
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
public function getAll()
{
    try {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    } catch (\PDOException $e) {
        return [];
    }
}
public function getNonAdminUsers()
{
    $stmt = $this->db->prepare("SELECT id, name, email FROM users WHERE is_admin = 0");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}