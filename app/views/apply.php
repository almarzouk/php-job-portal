<?php
// public/apply/{id} -> ApplyController::index($id)

require_once __DIR__ . '/public/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /public/auth/login');
    exit;
}

use Models\Job;
use Models\User;

$jobModel = new Job();
$job = $jobModel->findById($jobId);

$userModel = new User();
$user = $userModel->findById($_SESSION['user_id']);

if (!$job) {
    echo "<div class='alert alert-danger'>Stelle nicht gefunden.</div>";
    require_once __DIR__ . '/public/footer.php';
    exit;
}
?>

<div class="container mt-5 pt-5">
  <h2>Bewerbung für: <strong><?= htmlspecialchars($job['title']) ?></strong></h2>

  <?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>

  <form action="/public/apply/submit/<?= $job['id'] ?>" method="POST" enctype="multipart/form-data" class="mt-4 mb-5">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" disabled>
    </div>
    <div class="mb-3">
      <label class="form-label">E-Mail</label>
      <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
    </div>
    <div class="mb-3">
      <label for="message" class="form-label">Nachricht / Motivationsschreiben</label>
      <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
    </div>

    <?php if (!empty($user['cv'])): ?>
    <div class="mb-3">
      <label class="form-label">Ihr Lebenslauf</label><br>
      <a href="/public/uploads/<?= htmlspecialchars($user['cv']) ?>" target="_blank" class="btn btn-outline-primary">
        Aktuellen Lebenslauf ansehen
      </a>
      <input type="hidden" name="use_existing_cv" value="1">
    </div>
    <?php else: ?>
    <div class="mb-3">
      <label for="cv" class="form-label">Lebenslauf hochladen (PDF/DOC)</label>
      <input type="file" name="cv" id="cv" class="form-control" accept=".pdf,.doc,.docx" required>
    </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary">Absenden</button>
  </form>
</div>

<?php require_once __DIR__ . '/public/footer.php'; ?>