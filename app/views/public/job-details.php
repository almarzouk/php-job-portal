<?php
require_once 'header.php';

use Models\Application;

if (!isset($_SESSION)) session_start();

// تنظيف الرسائل القديمة إن وجدت
unset($_SESSION['error'], $_SESSION['success']);

// التحقق من حالة التقديم
$hasApplied = false;
if (isset($_SESSION['user_id'])) {
    $applicationModel = new Application();
    $hasApplied = $applicationModel->userHasAlreadyApplied($_SESSION['user_id'], $job['id']);
}
?>

<style>
body {
  font-family: 'Poppins', sans-serif;
  background-color: #f8fafc;
  color: #1e293b;
}

.job-header {
  background: linear-gradient(135deg, #3b82f6, #60a5fa);
  color: white;
  padding: 4rem 0;
  text-align: center;
  border-radius: 0 0 50% 50% / 10%;
  position: relative;
  margin-top: 100px;
  margin-bottom: 100px;
}

.job-header h1 {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.job-header p {
  font-size: 1.2rem;
  opacity: 0.95;
}

.job-details-container {
  max-width: 900px;
  margin: 0 auto;
  background: white;
  padding: 2rem;
  margin-top: -4rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  margin-bottom: 50px;
}

.job-meta {
  display: flex;
  gap: 1.5rem;
  flex-wrap: wrap;
  font-size: 0.95rem;
  color: #64748b;
  margin-bottom: 1rem;
}

.job-meta i {
  margin-right: 0.5rem;
  color: #3b82f6;
}

.job-description ol,
.job-description ul {
  margin: 1rem 0;
  padding-left: 1.5rem;
}

.job-description li {
  margin-bottom: 0.4rem;
  line-height: 1.6;
}

.back-btn {
  display: inline-block;
  margin-bottom: 2rem;
}

.apply-btn {
  background: #3b82f6;
  color: white;
  padding: 0.75rem 2rem;
  border: none;
  border-radius: 50px;
  font-weight: 600;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease;
}

.apply-btn:hover {
  background: #2563eb;
  transform: translateY(-2px);
}
</style>

<!-- Job Header -->
<section class="job-header">
  <div class="container">
    <h1><?= htmlspecialchars($job['title']) ?></h1>
    <p><?= htmlspecialchars($job['location']) ?> – <?= htmlspecialchars($job['category']) ?></p>
  </div>
</section>

<!-- Job Details -->
<section class="job-details-container container">
  <a href="/public/" class="btn btn-outline-secondary back-btn">⬅ Zurück zur Übersicht</a>

  <div class="job-meta">
    <div><i class="fas fa-calendar-alt"></i> Veröffentlicht am: <?= date('d.m.Y', strtotime($job['created_at'])) ?>
    </div>
    <div><i class="fas fa-building"></i> Unternehmen: <?= htmlspecialchars($job['company'] ?? 'Nicht angegeben') ?>
    </div>
    <div><i class="fas fa-clock"></i> Art: <?= htmlspecialchars($job['type'] ?? 'Vollzeit') ?></div>
  </div>

  <div class="job-description">
    <?= $job['description'] ?>
  </div>

  <div class="text-end">
    <?php if (isset($_SESSION['user_id'])): ?>
    <?php if ($hasApplied): ?>
    <button class="apply-btn" disabled>✅ Bereits beworben</button>
    <?php else: ?>
    <a href="/public/apply/showForm/<?= $job['id'] ?>" class="apply-btn">Jetzt bewerben</a>
    <?php endif; ?>
    <?php else: ?>
    <a href="/public/auth/login" class="apply-btn">Jetzt bewerben</a>
    <?php endif; ?>
  </div>
</section>

<?php require_once 'footer.php'; ?>