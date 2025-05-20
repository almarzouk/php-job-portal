<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php
use Models\Notification;

$notifications = [];
$unreadCount = 0;

if (!empty($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../models/Notification.php';
    $notificationModel = new Notification();
    $notifications = $notificationModel->getLatest($_SESSION['user_id'], 5);
    $unreadCount = $notificationModel->countUnread($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <title>Jobbörse – Deine Karriereplattform</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
  :root {
    --primary: #3b82f6;
    --primary-light: #60a5fa;
    --primary-dark: #2563eb;
    --accent: #f59e0b;
    --text-dark: #1e293b;
    --text-light: #64748b;
    --bg-light: #f8fafc;
    --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    --transition: all 0.3s ease;
  }

  body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--bg-light);
    color: var(--text-dark);
    line-height: 1.7;
  }

  /* Navbar */
  .navbar {
    padding: 1rem 2rem;
    background-color: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
  }

  .navbar-brand {
    font-weight: 700;
    color: var(--primary);
    font-size: 1.5rem;
  }

  .nav-link {
    font-weight: 500;
    margin: 0 0.5rem;
    position: relative;
  }

  .nav-link::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background-color: var(--primary);
    transition: var(--transition);
  }

  .nav-link:hover::after {
    width: 100%;
  }

  /* Hero Section */
  .hero {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: white;
    padding: 8rem 0;
    position: relative;
    overflow: hidden;
    border-radius: 0 0 30% 30% / 10%;
  }

  .hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('https://cdnjs.cloudflare.com/ajax/libs/Faker/3.1.0/images/abstract/1.jpg') center center/cover;
    opacity: 0.1;
  }

  .hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
  }

  .hero h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .hero p {
    font-size: 1.3rem;
    margin-bottom: 2rem;
    opacity: 0.9;
  }

  .search-bar {
    background: white;
    border-radius: 50px;
    padding: 0.5rem;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    display: flex;
    max-width: 700px;
    margin: 2rem auto 0;
  }

  .search-bar input {
    border: none;
    flex-grow: 1;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-size: 1rem;
  }

  .search-bar input:focus {
    outline: none;
  }

  .search-bar button {
    border-radius: 50px;
    padding: 0.75rem 1.75rem;
    font-weight: 600;
    border: none;
    background: var(--primary);
    color: white;
    transition: var(--transition);
  }

  .search-bar button:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
  }

  /* Categories */
  .categories {
    padding: 5rem 0;
  }

  .category-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    height: 100%;
  }

  .category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
  }

  .category-icon {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(59, 130, 246, 0.1);
    color: var(--primary);
    border-radius: 50%;
    font-size: 1.8rem;
    margin: 0 auto 1.5rem;
  }

  .category-card h4 {
    font-weight: 600;
    margin-bottom: 0.5rem;
  }

  .category-card p {
    color: var(--text-light);
    font-size: 0.9rem;
  }

  /* Job Listings */
  .jobs {
    padding: 5rem 0;
    background-color: #f1f5f9;
  }

  .section-header {
    text-align: center;
    margin-bottom: 3rem;
  }

  .section-header h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
  }

  .section-header p {
    color: var(--text-light);
    max-width: 700px;
    margin: 0 auto;
  }

  .job-card {
    background: white;
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid #f1f5f9;
  }

  .job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    border-color: rgba(59, 130, 246, 0.3);
  }

  .job-card .card-body {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }

  .job-card-header {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
  }

  .company-logo {
    width: 50px;
    height: 50px;
    background-color: rgba(59, 130, 246, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    margin-right: 1rem;
    color: var(--primary);
    font-size: 1.5rem;
  }

  .job-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-dark);
  }

  .job-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 1rem;
  }

  .job-meta-item {
    display: flex;
    align-items: center;
    color: var(--text-light);
    font-size: 0.9rem;
    margin-right: 1rem;
    margin-bottom: 0.5rem;
  }

  .job-meta-item i {
    font-size: 0.9rem;
    margin-right: 0.3rem;
    color: var(--primary);
  }

  .job-description {
    color: var(--text-light);
    margin-bottom: 1.5rem;
    flex-grow: 1;
  }

  .job-tags {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
  }

  .job-tag {
    background-color: rgba(59, 130, 246, 0.1);
    color: var(--primary);
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
  }

  .view-job-btn {
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 50px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: var(--transition);
    display: block;
    text-align: center;
    text-decoration: none;
  }

  .view-job-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    color: white;
  }

  /* CTA */
  .cta {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    padding: 6rem 0;
    position: relative;
    overflow: hidden;
  }

  .cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('https://cdnjs.cloudflare.com/ajax/libs/Faker/3.1.0/images/abstract/2.jpg') center center/cover;
    opacity: 0.1;
  }

  .cta-content {
    position: relative;
    z-index: 2;
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
  }

  .cta h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
  }

  .cta p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
  }

  .cta-btn {
    background: white;
    color: var(--primary);
    border: none;
    border-radius: 50px;
    padding: 1rem 2.5rem;
    font-weight: 600;
    font-size: 1.1rem;
    transition: var(--transition);
    display: inline-block;
    text-decoration: none;
  }

  .cta-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    color: var(--primary);
  }

  /* About */
  .about {
    padding: 6rem 0;
    background-color: white;
  }

  .about-text {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
  }

  .about h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--text-dark);
  }

  .about p {
    color: var(--text-light);
    font-size: 1.1rem;
    margin-bottom: 2rem;
  }

  .stats {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 3rem;
  }

  .stat-item {
    text-align: center;
    padding: 0 2rem;
    margin-bottom: 2rem;
  }

  .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 0.5rem;
  }

  .stat-label {
    color: var(--text-light);
    font-size: 1rem;
  }

  /* Footer */
  .footer {
    background-color: #1e293b;
    color: white;
    padding: 4rem 0 2rem;
  }

  .footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .footer-column {
    flex: 1;
    min-width: 250px;
    margin-bottom: 2rem;
    padding: 0 1rem;
  }

  .footer-logo {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: white;
  }

  .footer-about {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 1.5rem;
  }

  .footer-heading {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: white;
  }

  .footer-links {
    list-style: none;
    padding: 0;
  }

  .footer-link {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 0.8rem;
    transition: var(--transition);
    text-decoration: none;
    display: block;
  }

  .footer-link:hover {
    color: white;
    transform: translateX(5px);
  }

  .social-links {
    display: flex;
    margin-top: 1.5rem;
  }

  .social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    margin-right: 0.8rem;
    color: white;
    transition: var(--transition);
    text-decoration: none;
  }

  .social-link:hover {
    background-color: var(--primary);
    transform: translateY(-3px);
    color: white;
  }

  .footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 2rem;
    margin-top: 2rem;
    text-align: center;
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
  }

  /* Responsive */
  @media (max-width: 992px) {
    .hero h1 {
      font-size: 3rem;
    }

    .search-bar {
      flex-direction: column;
      border-radius: 20px;
    }

    .search-bar input {
      margin-bottom: 0.5rem;
    }

    .search-bar button {
      width: 100%;
    }
  }

  @media (max-width: 768px) {
    .hero h1 {
      font-size: 2.5rem;
    }

    .hero p {
      font-size: 1.1rem;
    }

    .section-header h2 {
      font-size: 2rem;
    }

    .cta h2 {
      font-size: 2rem;
    }

    .about h2 {
      font-size: 2rem;
    }
  }

  @media (max-width: 576px) {
    .hero {
      padding: 6rem 0;
    }

    .hero h1 {
      font-size: 2rem;
    }

    .section-header h2 {
      font-size: 1.8rem;
    }

    .cta h2 {
      font-size: 1.8rem;
    }

    .cta p {
      font-size: 1rem;
    }

    .about h2 {
      font-size: 1.8rem;
    }

    .about p {
      font-size: 1rem;
    }
  }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="/public">JobMatch</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link" href="/public">Startseite</a></li>
          <li class="nav-item"><a class="nav-link" href="/public/#jobs">Jobs</a></li>
          <li class="nav-item"><a class="nav-link" href="/public/#about">Über uns</a></li>

          <?php if (!empty($_SESSION['user_id'])): ?>
          <!-- إشعارات -->
          <li class="nav-item dropdown me-3">
            <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-bell"></i>
              <?php if ($unreadCount > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= $unreadCount ?>
              </span>
              <?php endif; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-2" style="width: 300px;"
              aria-labelledby="notificationsDropdown">
              <li class="dropdown-header fw-bold d-flex justify-content-between align-items-center">
                Benachrichtigungen
                <?php if ($unreadCount > 0): ?>
                <a href="/public/userprofile/markAllAsRead" class="btn btn-sm btn-link text-decoration-none">Alle
                  lesen</a>
                <?php endif; ?>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>

              <?php if (count($notifications) === 0): ?>
              <li class="text-muted px-3">Keine neuen Nachrichten</li>
              <?php else: ?>
              <?php foreach ($notifications as $note): ?>
              <li class="px-3 small mb-2 <?= !$note['is_read'] ? 'fw-bold' : '' ?>">
                <?= htmlspecialchars($note['message']) ?>
                <div class="text-muted small"><?= date('d.m.Y H:i', strtotime($note['created_at'])) ?></div>

                <?php if (!$note['is_read']): ?>
                <div class="mt-1">
                  <a href="/public/userprofile/markAsRead/<?= $note['id'] ?>"
                    class="btn btn-sm btn-outline-success btn-sm">
                    Als gelesen markieren
                  </a>
                </div>
                <?php endif; ?>
              </li>
              <?php endforeach; ?>
              <?php endif; ?>
            </ul>
          </li>

          <!-- قائمة المستخدم -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown">
              👤 <?= htmlspecialchars($_SESSION['user_name'] ?? 'Benutzer'); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
              <li><a class="dropdown-item" href="/public/admin/profile">Profil</a></li>
              <li><a class="dropdown-item" href="/public/admin/dashboard">Dashboard</a></li>
              <?php else: ?>
              <li><a class="dropdown-item" href="/public/userprofile">
                  <i class="bi bi-person-circle me-2"></i> Profil
                </a></li>
              <li><a class="dropdown-item" href="/public/userprofile/bewerbungen">
                  <i class="bi bi-file-earmark-text me-2"></i> Meine Bewerbungen
                </a></li>
              <li><a class="dropdown-item" href="/public/favorite">
                  <i class="bi bi-heart-fill text-danger me-2"></i> Meine Favoriten
                </a></li>
              <li><a class="dropdown-item" href="/public/messages">
                  <i class="bi bi-chat-dots me-2"></i> Nachrichten
                </a></li>
              <?php endif; ?>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="/public/auth/logout">Abmelden</a></li>
            </ul>
          </li>
          <?php else: ?>
          <!-- زوار غير مسجلين -->
          <li class="nav-item ms-lg-3"><a class="btn btn-outline-primary" href="/public/auth/login">Anmelden</a></li>
          <li class="nav-item ms-lg-2"><a class="btn btn-primary" href="/public/auth/register">Registrieren</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>