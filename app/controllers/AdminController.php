<?php

use Models\User;
use Models\Job;
use Models\Application;
class AdminController
{
    public function dashboard()
    {
        require_once '../app/views/admin/dashboard.php';
    }
    public function users()
    {
        $userModel = new \Models\User(); // تأكد من الـ namespace الصحيح
        $users = $userModel->getAll();
    
        // تمرير المتغير إلى الـ View
        require_once '../app/views/admin/users.php';
    }
    public function jobs()
    {
        require_once '../app/views/admin/jobs.php';
    }


public function addJob()
{
    require_once '../app/views/admin/add-job.php';
}


public function deleteJob($id)
{
    $jobModel = new Job();

    // حاول حذف الوظيفة
    $deleted = $jobModel->deleteById($id);

    if ($deleted) {
        $_SESSION['success'] = '✅ Die Stelle wurde erfolgreich gelöscht.';
    } else {
        $_SESSION['error'] = '❌ Fehler beim Löschen der Stelle.';
    }

    // إعادة التوجيه إلى صفحة عرض الوظائف
    header('Location: /public/admin/jobs');
    exit;
}
public function editJob($id)
{
    $jobModel = new Job();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $location = $_POST['location'];
        $category = $_POST['category'];
        $description = $_POST['description'];

        $jobModel->updateJob($id, $title, $location, $category, $description);
        $_SESSION['success'] = 'Die Stelle wurde aktualisiert.';
        header('Location: /public/admin/jobs');
        exit;
    }

    $job = $jobModel->findById($id);
    if (!$job) {
        $_SESSION['error'] = 'Stelle nicht gefunden.';
        header('Location: /public/admin/jobs');
        exit;
    }

    require_once '../app/views/admin/edit-job.php';
}

public function userDetails($id)
{
    $userModel = new User();
    $user = $userModel->findById($id);

    if (!$user) {
        $_SESSION['error'] = 'Benutzer nicht gefunden.';
        header('Location: /public/admin/users');
        exit;
    }

    require_once '../app/views/admin/user-details.php';
}
public function promoteUser($id)
{
    $userModel = new User();
    $user = $userModel->findById($id);

    if ($user && !$user['is_admin']) {
        $userModel->setAdmin($id);
        $_SESSION['success'] = 'Benutzer wurde zum Admin befördert.';
    } else {
        $_SESSION['error'] = 'Aktion nicht möglich.';
    }

    header('Location: /public/admin/users');
    exit;
}
public function applications()
{
    $jobModel = new Job();
    $applicationModel = new Application();

    if (isset($_GET['job_id'])) {
        $jobId = (int)$_GET['job_id'];
        $job = $jobModel->findById($jobId);
        $applications = $applicationModel->findByJobId($jobId);
        require_once '../app/views/admin/applications.php';
    } else {
        $jobsWithCounts = $jobModel->getJobsWithApplicationCounts();
        require_once '../app/views/admin/applications.php';
    }
}
    public function editUser($id)
{
    $userModel = new User();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];

        $userModel->editUser($id, $name, $email);
        $_SESSION['success'] = 'Benutzerdaten wurden aktualisiert.';
        header('Location: /public/admin/users');
        exit;
    }

    $user = $userModel->findById($id);
    if (!$user) {
        $_SESSION['error'] = 'Benutzer nicht gefunden.';
        header('Location: /public/admin/users');
        exit;
    }

    require_once '../app/views/admin/edit-user.php';
}

public function deleteUser($id)
{
    $userModel = new User();
    $user = $userModel->findById($id);

    if ($user && !$user['is_admin']) {
        $userModel->deleteById($id);
        $_SESSION['success'] = 'Benutzer wurde gelöscht.';
    } else {
        $_SESSION['error'] = 'Admins können nicht gelöscht werden.';
    }

    header('Location: /public/admin/users');
    exit;
}

public function profile()
{
    if (!isset($_SESSION)) session_start();
    if (empty($_SESSION['is_admin'])) {
        header('Location: /public/auth/login');
        exit;
    }

    $userModel = new User(); // ✅ بدون Backslash
    $admin = $userModel->findById($_SESSION['user_id']);

    require_once '../app/views/admin/profile.php';
}

public function updateProfile()
{
    if (!isset($_SESSION)) session_start();
    if (empty($_SESSION['is_admin'])) {
        header('Location: /public/auth/login');
        exit;
    }

    $userModel = new \Models\User();

    $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email'])
    ];

    if (!empty($_POST['password'])) {
        $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $userModel->update($_SESSION['user_id'], $data);
    $_SESSION['success'] = "Profil erfolgreich aktualisiert.";

    header('Location: /public/admin/profile');
    exit;
}
public function toggleJobVisibility($jobId)
{
    $jobModel = new Job();
    $job = $jobModel->findById($jobId);

    if (!$job) {
        $_SESSION['error'] = 'Stelle nicht gefunden.';
        header('Location: /public/admin/applications');
        exit;
    }

    $newVisibility = $job['is_visible'] ? 0 : 1;
    $jobModel->updateVisibility($jobId, $newVisibility);

    $_SESSION['success'] = $newVisibility ? 'Die Stelle ist jetzt sichtbar.' : 'Die Stelle wurde versteckt.';
    header('Location: /public/admin/applications');
    exit;
}
public function jobApplications($jobId)
{
    $jobModel = new \Models\Job();
    $applicationModel = new \Models\Application();

    $job = $jobModel->findById($jobId);
    $applications = $applicationModel->getApplicationsByJobId($jobId);

    require_once '../app/views/admin/applications.php';
}

public function changeApplicationStatus($applicationId, $newStatus)
{
    $validStatuses = ['pending', 'viewed', 'accepted', 'rejected'];

    if (!in_array($newStatus, $validStatuses)) {
        $_SESSION['error'] = "Ungültiger Status.";
        header('Location: /public/admin/applications');
        exit;
    }

    $applicationModel = new \Models\Application();
    $userModel = new \Models\User();
    $jobModel = new \Models\Job();
    $notificationModel = new \Models\Notification();

    // ✅ جلب بيانات الطلب
    $application = $applicationModel->findById($applicationId);
    if (!$application) {
        $_SESSION['error'] = "Bewerbung nicht gefunden.";
        header('Location: /public/admin/applications');
        exit;
    }

    // ✅ تحديث الحالة
    $updated = $applicationModel->updateStatus($applicationId, $newStatus);

    if ($updated) {
        $_SESSION['success'] = "Status erfolgreich geändert.";

        // ✅ إرسال إشعار للمستخدم
        $job = $jobModel->findById($application['job_id']);
        $jobTitle = $job ? $job['title'] : 'eine Stelle';

        $statusText = match($newStatus) {
            'viewed'   => '🕵️‍♂️ Ihre Bewerbung wurde angesehen.',
            'accepted' => '✅ Ihre Bewerbung für "' . $jobTitle . '" wurde akzeptiert!',
            'rejected' => '❌ Ihre Bewerbung für "' . $jobTitle . '" wurde leider abgelehnt.',
            default    => '',
        };

        if (!empty($statusText)) {
            $notificationModel->create($application['user_id'], $statusText);
        }

    } else {
        $_SESSION['error'] = "Fehler beim Aktualisieren des Status.";
    }

    $referer = $_SERVER['HTTP_REFERER'] ?? '/public/admin/applications';
    header("Location: $referer");
    exit;
}
public function scheduleInterview()
{
    if (!isset($_POST['application_id']) || !isset($_POST['interview_date'])) {
        $_SESSION['error'] = 'Ungültige Eingaben.';
        header('Location: /public/admin/applications');
        exit;
    }

    $applicationModel = new \Models\Application();
    $applicationModel->updateInterviewDetails(
        $_POST['application_id'],
        $_POST['interview_date'],
        $_POST['interview_link'] ?? ''
    );

    // حدّث الحالة أيضًا إلى accepted
    $applicationModel->updateStatus($_POST['application_id'], 'accepted');

    $_SESSION['success'] = 'Interview geplant & Bewerbung akzeptiert.';
    header('Location: /public/admin/applications');
    exit;
}
public function messages()
{
    require_once __DIR__ . '/MessagesController.php';
    $controller = new \MessagesController();
    $controller->messages();
}
}