<?php

use Models\Message;
use Models\User;

class MessagesController
{
    public function index()
{
    // ببساطة إعادة التوجيه إلى الدالة messages()
    $this->messages();
}
    // عرض صفحة الرسائل (admin أو user)
    public function messages()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /public/auth/login");
            exit;
        }

        $messageModel = new Message();
        $userModel = new User();

        $currentUserId = $_SESSION['user_id'];
        $isAdmin = $_SESSION['is_admin'] ?? 0;

        // المستخدمون الذين تم التراسل معهم
        if ($isAdmin) {
            $chatUsers = $userModel->getNonAdminUsers();
        } else {
            $chatUsers = $messageModel->getChatUsers($currentUserId);
        }

        // واجهة مخصصة لكل نوع مستخدم
        if ($isAdmin) {
            require_once '../app/views/admin/messages.php';
        } else {
            require_once '../app/views/user/messages.php';
        }
    }

    // API: جلب الرسائل
    public function fetch($partnerId)
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) exit;

        $currentUserId = $_SESSION['user_id'];
        $messageModel = new Message();

        $messages = $messageModel->getMessagesBetween($currentUserId, $partnerId);

        header('Content-Type: application/json');
        echo json_encode($messages);
    }

    // API: إرسال رسالة
    public function send()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) exit;

        $senderId = $_SESSION['user_id'];
        $receiverId = $_POST['receiver_id'] ?? null;
        $content = trim($_POST['content'] ?? $_POST['message'] ?? '');

        if (!$receiverId || empty($content)) exit;

        $messageModel = new Message();
        $messageModel->sendMessage($senderId, $receiverId, $content);
        echo 'ok';
    }

    // API: تعليم الرسائل كمقروءة
    public function markAsRead($partnerId)
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) exit;

        $currentUserId = $_SESSION['user_id'];
        $messageModel = new Message();
        $messageModel->markMessagesAsRead($partnerId, $currentUserId);
        echo 'done';
    }
    public function getChatUsers()
{
    if (!isset($_SESSION)) session_start();
    if (!isset($_SESSION['user_id'])) exit;

    $currentUserId = $_SESSION['user_id'];
    $messageModel = new \Models\Message();

    $users = $messageModel->getChatUsers($currentUserId);

    header('Content-Type: application/json');
    echo json_encode($users);
}
}