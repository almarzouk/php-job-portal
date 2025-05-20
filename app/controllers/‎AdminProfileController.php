<?php
require_once __DIR__ . '/../init.php';

use Models\User;

class AdminprofileController
{
    public function index()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
            header('Location: /public/auth/login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);

        require_once __DIR__ . '/../views/admin/profile.php';
    }

    public function update()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
            header('Location: /public/auth/login');
            exit;
        }

        $userModel = new User();

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $updatedData = [
            'name' => $name,
            'email' => $email
        ];

        if (!empty($password)) {
            $updatedData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($_SESSION['user_id'], $updatedData);
        $_SESSION['success'] = "✅ Admin-Profil wurde erfolgreich aktualisiert.";

        header('Location: /public/admin/profile');
        exit;
    }
}