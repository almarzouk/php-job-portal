<?php

use Models\Favorite;
use Models\Job;
class FavoriteController
{
    public function toggle($jobId)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /public/auth/login');
            exit;
        }

        $favorite = new Favorite();
        $favorite->toggle($_SESSION['user_id'], $jobId);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function index()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /public/auth/login');
            exit;
        }

        $favoriteModel = new Favorite();
        $jobModel = new Job();

        // جلب قائمة الوظائف المحفوظة
        $favoriteJobIds = $favoriteModel->getFavoriteJobIdsByUser($_SESSION['user_id']);

        $favorites = [];
        foreach ($favoriteJobIds as $jobId) {
            $job = $jobModel->findById($jobId);
            if ($job) {
                $favorites[] = $job;
            }
        }

        // تمرير المتغير إلى الـ View
        require_once '../app/views/user/favorites.php';
    }
}