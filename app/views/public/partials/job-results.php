<div class="col-md-6 col-lg-4 mb-4">
  <div class="job-card">
    <div class="card-body">
      <div class="job-card-header">
        <div class="company-logo">
          <i class="fas fa-building"></i>
        </div>
        <div>
          <h5 class="job-title"><?= htmlspecialchars($job['title']) ?></h5>
          <div class="company-name"><?= htmlspecialchars($job['company'] ?? 'Unternehmen') ?></div>
        </div>
      </div>

      <div class="job-meta">
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

      <div class="job-description">
        <?= mb_strimwidth(strip_tags($job['description']), 0, 100, "...") ?>
      </div>

      <div class="job-tags">
        <span class="job-tag">Bewerbung einfach</span>
        <span class="job-tag">Sofort verfügbar</span>
      </div>

      <a href="/public/jobs/view/<?= $job['id'] ?>" class="view-job-btn">Details ansehen</a>
    </div>
  </div>
</div>