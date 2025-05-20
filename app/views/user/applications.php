<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php require_once __DIR__ . '/../public/header.php'; ?>
<div class="container mt-5 pt-5">
  <h2 class="mb-4"><i class="fas fa-file-alt me-2"></i> Meine Bewerbungen</h2>

  <?php if (isset($applications) && count($applications) > 0): ?>
  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Stellentitel</th>
        <th>Status</th>
        <th>Nachricht</th>
        <th>Interview</th>
        <th>CV</th>
        <th>Datum</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($applications as $index => $app): ?>
      <tr>
        <td><?= $index + 1 ?></td>
        <td><?= htmlspecialchars($app['job_title']) ?></td>
        <td><span class="badge bg-info text-dark"><?= htmlspecialchars($app['status']) ?></span></td>
        <td><?= nl2br(htmlspecialchars($app['message'])) ?></td>
        <td>
          <?php if ($app['interview_date']): ?>
          <div><strong><?= date('d.m.Y H:i', strtotime($app['interview_date'])) ?></strong></div>
          <?php endif; ?>
          <?php if ($app['interview_link']): ?>
          <div><a href="<?= htmlspecialchars($app['interview_link']) ?>" target="_blank">Zoom/Link</a></div>
          <?php endif; ?>
        </td>
        <td><a href="/uploads/<?= htmlspecialchars($app['file']) ?>" class="btn btn-sm btn-outline-primary"
            target="_blank">Herunterladen</a></td>
        <td><?= date('d.m.Y', strtotime($app['created_at'])) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
  <div class="alert alert-warning">Sie haben sich noch auf keine Stelle beworben.</div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../public/footer.php'; ?>