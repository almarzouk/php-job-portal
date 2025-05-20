<?php

use Models\User; // ✅ استدعاء الكلاس باستخدام namespace الصحيح

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        session_start();
    }

    // عرض صفحة التسجيل
    public function register()
    {
        require_once '../app/views/auth/register.php';
    }

    // تنفيذ عملية التسجيل
    public function doRegister()
    {
        $name     = $_POST['name'] ?? '';
        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        if (!$name || !$email || !$password || !$confirm) {
            $_SESSION['error'] = '❌ Bitte füllen Sie alle Felder aus.';
            header('Location: /public/auth/register');
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['error'] = '❌ Die Passwörter stimmen nicht überein.';
            header('Location: /public/auth/register');
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $result = $this->userModel->register($name, $email, $hashedPassword);

        if ($result === true) {
            $_SESSION['success'] = '✅ Registrierung erfolgreich. Bitte anmelden.';
            header('Location: /public/auth/login');
        } else {
            $_SESSION['error'] = $result;
            header('Location: /public/auth/register');
        }
    }

    // عرض صفحة تسجيل الدخول
    public function login()
    {
        require_once '../app/views/auth/login.php';
    }

    // تنفيذ عملية تسجيل الدخول
    public function doLogin()
{
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $_SESSION['error'] = '❌ Bitte E-Mail und Passwort eingeben.';
        header('Location: /public/auth/login');
        exit;
    }

    $result = $this->userModel->login($email, $password);

    if (is_array($result)) {
        $_SESSION['user_id']    = $result['id'];
        $_SESSION['user_name']  = $result['name'];
        $_SESSION['user_email'] = $result['email']; // ✅ هنا الإضافة
        $_SESSION['is_admin']   = $result['is_admin'];

        if ($result['is_admin']) {
            header('Location: /public/admin/dashboard');
        } else {
            header('Location: /public/');
        }
        exit;
    } else {
        $_SESSION['error'] = $result;
        header('Location: /public/auth/login');
    }
}

    // تسجيل الخروج
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /public/auth/login');
        exit;
    }
}