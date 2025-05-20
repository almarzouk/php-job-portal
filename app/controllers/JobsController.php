<?php
use Models\Job;
class JobsController
{
    public function index()
    {
        $jobModel = new Job();

        $query = $_GET['q'] ?? '';
        if (!empty($query)) {
            $jobs = $jobModel->search($query); // تحتاج إلى إنشائها
        } else {
            $jobs = $jobModel->getAll();
        }

        require_once '../app/views/public/index.php';
    }

    public function view($id)
    {
        $jobModel = new Job();
        $job = $jobModel->findById($id);

        if (!$job) {
            $_SESSION['error'] = 'Stelle nicht gefunden.';
            header('Location: /public/index');
            exit;
        }

        require_once __DIR__ . '/../views/public/job-details.php'; // ✅
    }
    public function suggest()
{
    header('Content-Type: application/json');
    $query = $_GET['term'] ?? '';
    
    if (strlen($query) < 2) {
        echo json_encode([]);
        exit;
    }

    $jobModel = new Job();
    $results = $jobModel->search($query);
    $titles = array_map(fn($job) => $job['title'], $results);
    
    echo json_encode(array_values(array_unique($titles)));
    exit;
}
public function ajaxSearch()
{
    $query = $_GET['q'] ?? '';
    $jobModel = new \Models\Job();
    $jobs = $query ? $jobModel->search($query) : $jobModel->getAll();

    foreach ($jobs as $job) {
        require '../app/views/public/partials/job-results.php';
    }
}
}