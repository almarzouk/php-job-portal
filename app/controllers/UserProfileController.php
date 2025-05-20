<?php

use Models\User;

class UserProfileController
{
    public function index()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /public/auth/login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);

        require_once __DIR__ . '/../views/user/profile.php';
    }

    public function update()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /public/auth/login');
            exit;
        }

        $userModel = new User();

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $updatedData = [
            'name'  => $name,
            'email' => $email,
        ];

        // رفع السيرة الذاتية
        if (!empty($_FILES['cv']['name'])) {
            $cv = $_FILES['cv'];
            $filename = uniqid() . '_' . basename($cv['name']);
            $destination = __DIR__ . '/../../public/uploads/' . $filename;

            if (move_uploaded_file($cv['tmp_name'], $destination)) {
                $updatedData['cv'] = $filename;
            } else {
                $_SESSION['error'] = "Fehler beim Hochladen des Lebenslaufs.";
                header('Location: /public/userprofile');
                exit;
            }
        }

        if (!empty($password)) {
            $updatedData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($_SESSION['user_id'], $updatedData);
        $_SESSION['success'] = "Profil wurde aktualisiert.";
        header('Location: /public/userprofile');
        exit;
    }

    public function bewerbungen()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /public/auth/login');
            exit;
        }

        $applicationModel = new \Models\Application();
        $applications = $applicationModel->getByUserId($_SESSION['user_id']);

        require_once '../app/views/user/applications.php';
    }
    public function markAsRead($id)
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /public/auth/login');
            exit;
        }
    
        $notificationModel = new \Models\Notification();
        $notificationModel->markAsRead($id);
    
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    public function markAllAsRead()
{
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: /public/auth/login');
        exit;
    }

    $notificationModel = new \Models\Notification();
    $notificationModel->markAllAsRead($_SESSION['user_id']);

    // إعادة التوجيه إلى الصفحة السابقة
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/public/userprofile'));
    exit;
}
}