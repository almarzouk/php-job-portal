<?php

use Models\Job;
use Models\Application;

class ApplyController
{
  public function index($jobId)
  {
      session_start();
      if (!isset($_SESSION['user_id'])) {
          header('Location: /public/auth/login');
          exit;
      }

      $jobModel = new Job();
      $job = $jobModel->findById($jobId);

      if (!$job) {
          $_SESSION['error'] = "Stelle nicht gefunden.";
          header('Location: /public/');
          exit;
      }

      require_once '../app/views/apply.php';
  }

    public function show($jobId)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bitte melden Sie sich zuerst an.";
            header('Location: /public/auth/login');
            exit;
        }

        $jobModel = new \Models\Job();
        $job = $jobModel->findById($jobId);

        if (!$job) {
            $_SESSION['error'] = "Job nicht gefunden.";
            header('Location: /public/');
            exit;
        }

        require_once __DIR__ . '/../views/applications/apply.php';
    }

    public function submit($jobId)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bitte melden Sie sich zuerst an.";
            header('Location: /public/auth/login');
            exit;
        }
    
        $message = trim($_POST['message'] ?? '');
        if (!$jobId || !$message) {
            $_SESSION['error'] = "Bitte füllen Sie alle Felder aus.";
            header("Location: /public/apply/$jobId");
            exit;
        }
    
        // التحقق من التكرار
        $applicationModel = new \Models\Application();
        if ($applicationModel->userHasAlreadyApplied($_SESSION['user_id'], $jobId)) {
            $_SESSION['error'] = "Sie haben sich bereits für diese Stelle beworben.";
            header("Location: /public/apply/$jobId");
            exit;
        }
    
        // جلب بيانات المستخدم والوظيفة
        $userModel = new \Models\User();
        $user = $userModel->findById($_SESSION['user_id']);
    
        $jobModel = new \Models\Job();
        $job = $jobModel->findById($jobId);
    
        if (empty($user['cv'])) {
            $_SESSION['error'] = "⚠️ Bitte laden Sie zuerst Ihren Lebenslauf in Ihrem Profil hoch.";
            header("Location: /public/apply/$jobId");
            exit;
        }
    
        // إنشاء الطلب
        $applicationModel->create([
            'user_id' => $_SESSION['user_id'],
            'job_id'  => $jobId,
            'file'    => $user['cv'],
            'message' => $message,
            'name'    => $user['name'],
            'email'   => $user['email'],
        ]);
    
        // 🔔 إرسال إشعار لكل مسؤول
        $notificationModel = new \Models\Notification();
        $admins = $userModel->getAllAdmins(); // تحتاج لإنشائها
    
        foreach ($admins as $admin) {
            $notificationModel->create($admin['id'], "📄 Neuer Antrag für die Stelle: " . $job['title']);
        }
    
        $_SESSION['success'] = "✅ Ihre Bewerbung wurde erfolgreich eingereicht.";
        header('Location: /public/userprofile');
        exit;
    }
    public function showForm($jobId)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bitte einloggen, um sich zu bewerben.";
            header("Location: /public/auth/login");
            exit;
        }

        // جلب بيانات الوظيفة
        $jobModel = new \Models\Job();
        $job = $jobModel->findById($jobId);
        if (!$job) {
            $_SESSION['error'] = "Stelle nicht gefunden.";
            header("Location: /public/");
            exit;
        }

        // تحقق هل المستخدم قدم من قبل
        $appModel = new \Models\Application();
        $hasApplied = $appModel->userHasAlreadyApplied($_SESSION['user_id'], $jobId);

        if ($hasApplied) {
            $_SESSION['error'] = "Sie haben sich bereits für diese Stelle beworben.";
            header("Location: /public/user/bewerbungen");
            exit;
        }

        // عرض النموذج
        require_once __DIR__ . '/../views/apply.php';
    }
}