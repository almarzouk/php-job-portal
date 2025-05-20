<?php 
 require_once __DIR__ . '/../public/header.php';
use Models\Job;

$jobModel = new Job();
$query = $_GET['q'] ?? '';
$jobs = $query ? $jobModel->search($query) : $jobModel->getAll();
?>

<div class="container mt-5 pt-5">
  <h2 class="mb-4 fw-bold"><i class="fas fa-heart text-danger me-2"></i>Meine gespeicherten Jobs</h2>
  <?php if (count($favorites) > 0): ?>
  <div class="row">
    <?php foreach ($favorites as $job): ?>
    <div class="col-md-6 col-lg-4 mb-4">
      <div class="job-card">
        <div class="card-body">
          <div class="job-card-header d-flex justify-content-between align-items-start">
            <div class="d-flex">
              <div class="company-logo me-3">
                <i class="fas fa-building"></i>
              </div>
              <div>
                <h5 class="job-title mb-1"><?= htmlspecialchars($job['title']) ?></h5>
                <div class="company-name"><?= htmlspecialchars($job['company'] ?? 'Unternehmen') ?></div>
              </div>
            </div>

            <form action="/public/favorite/toggle/<?= $job['id'] ?>" method="POST">
              <button type="submit" class="btn btn-sm btn-danger" title="Entfernen">
                <i class="fas fa-heart-broken"></i>
              </button>
            </form>
          </div>

          <div class="job-meta mt-3">
            <div class="job-meta-item">
              <i class="fas fa-map-marker-alt"></i>
              <span><?= htmlspecialchars($job['location']) ?></span>
            </div>
            <div class="job-meta-item">
              <i class="fas fa-briefcase"></i>
              <span><?= htmlspecialchars($job['category']) ?></span>
            </div>
            <div class="job-meta-item">
              <i class="fas fa-clock"></i>
              <span><?= htmlspecialchars($job['type'] ?? 'Vollzeit') ?></span>
            </div>
          </div>

          <div class="job-description mt-3">
            <?= mb_strimwidth(strip_tags($job['description']), 0, 100, "...") ?>
          </div>

          <div class="job-tags mt-2">
            <span class="job-tag">Favorit</span>
            <span class="job-tag">Schnell bewerben</span>
          </div>

          <a href="/public/jobs/view/<?= $job['id'] ?>" class="view-job-btn mt-3">Details ansehen</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="alert alert-info mt-4">Du hast noch keine Jobs gespeichert.</div>
  <?php endif; ?>
</div>


<?php require_once __DIR__ . '/../public/footer.php'; ?>