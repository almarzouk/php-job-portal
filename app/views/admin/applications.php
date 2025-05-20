<?php require_once 'header.php'; ?>

<?php if (isset($job) && isset($applications)): ?>
<h3>📄 Bewerbungen für: <strong><?= htmlspecialchars($job['title']) ?></strong></h3>

<?php if (count($applications) === 0): ?>
<div class="alert alert-warning mt-3">Keine Bewerbungen für diese Stelle.</div>
<?php else: ?>
<table class="table table-bordered mt-4">
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Email</th>
      <th>CV</th>
      <th>Nachricht</th>
      <th>Status</th>
      <th>Interview</th>
      <th>Aktion</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($applications as $index => $app): ?>
    <tr>
      <td><?= $index + 1 ?></td>
      <td><?= htmlspecialchars($app['name']) ?></td>
      <td><?= htmlspecialchars($app['email']) ?></td>
      <td>
        <?php if (!empty($app['file'])): ?>
        <a href="/public/uploads/<?= htmlspecialchars($app['file']) ?>" target="_blank">Download</a>
        <?php else: ?>
        <span class="text-muted">Kein CV</span>
        <?php endif; ?>
      </td>
      <td><?= nl2br(htmlspecialchars($app['message'] ?? '')) ?></td>
      <td><span class="badge bg-info text-dark"><?= htmlspecialchars($app['status']) ?></span></td>
      <td>
        <?php if (!empty($app['interview_date'])): ?>
        <div><strong><?= date('d.m.Y H:i', strtotime($app['interview_date'])) ?></strong></div>
        <?php endif; ?>
        <?php if (!empty($app['interview_link'])): ?>
        <div><a href="<?= htmlspecialchars($app['interview_link']) ?>" target="_blank">Zoom/Link</a></div>
        <?php endif; ?>
      </td>
      <td>
        <a href="/public/admin/changeApplicationStatus/<?= $app['id'] ?>/viewed"
          class="btn btn-sm btn-secondary">Gesehen</a>

        <!-- زر يفتح المودال -->
        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#interviewModal"
          data-id="<?= $app['id'] ?>">Akzeptieren</button>

        <a href="/public/admin/changeApplicationStatus/<?= $app['id'] ?>/rejected"
          class="btn btn-sm btn-danger">Ablehnen</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<!-- Modal für Interview-Planung -->
<div class="modal fade" id="interviewModal" tabindex="-1" aria-labelledby="interviewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="/public/admin/scheduleInterview">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="interviewModalLabel">Interview planen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="application_id" id="modalApplicationId">
          <div class="mb-3">
            <label class="form-label">Datum & Uhrzeit</label>
            <input type="datetime-local" name="interview_date" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Interview-Link (optional)</label>
            <input type="url" name="interview_link" class="form-control" placeholder="https://zoom.us/...">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Speichern</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var modal = document.getElementById('interviewModal');
  modal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var appId = button.getAttribute('data-id');
    document.getElementById('modalApplicationId').value = appId;
  });
});
</script>

<?php else: ?>
<h3>📂 Liste aller Stellen und Bewerbungen</h3>

<?php if (isset($jobsWithCounts) && count($jobsWithCounts) > 0): ?>
<table class="table table-bordered mt-3">
  <thead class="table-dark">
    <tr>
      <th>Stellentitel</th>
      <th>Anzahl Bewerbungen</th>
      <th>Status</th>
      <th>Aktionen</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($jobsWithCounts as $job): ?>
    <tr>
      <td><?= htmlspecialchars($job['title'] ?? '') ?></td>
      <td><?= $job['total_applications'] ?? 0 ?></td>
      <td>
        <?php if (isset($job['is_visible']) && $job['is_visible']): ?>
        <span class="badge bg-success">Anzeigen</span>
        <?php else: ?>
        <span class="badge bg-secondary">Versteckt</span>
        <?php endif; ?>
      </td>
      <td>
        <a href="/public/admin/jobApplications/<?= $job['id'] ?>" class="btn btn-sm btn-primary">Details</a>
        <a href="/public/admin/toggleJobVisibility/<?= $job['id'] ?>" class="btn btn-sm btn-warning">
          <?php if (isset($job['is_visible']) && $job['is_visible']): ?> Verstecken <?php else: ?> Anzeigen
          <?php endif; ?>
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<div class="alert alert-info mt-4">Keine Stellen oder Bewerbungen vorhanden.</div>
<?php endif; ?>
<?php endif; ?>

<?php require_once 'footer.php'; ?>