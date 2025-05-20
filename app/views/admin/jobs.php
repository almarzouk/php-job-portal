<?php
require_once 'header.php';
use Models\Job;
$jobModel = new Job();
$jobs = $jobModel->getAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2>📄 Stellenanzeigen</h2>
  <a href="/public/admin/addJob" class="btn btn-success">➕ Neue Stelle</a>
</div>

<table class="table table-bordered table-striped table-hover">
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Titel</th>
      <th>Ort</th>
      <th>Kategorie</th>
      <th>Erstellt am</th>
      <th>Aktionen</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($jobs) > 0): ?>
    <?php foreach ($jobs as $index => $job): ?>
    <tr>
      <td><?= $index + 1 ?></td>
      <td><?= htmlspecialchars($job['title']) ?></td>
      <td><?= htmlspecialchars($job['location']) ?></td>
      <td><?= htmlspecialchars($job['category']) ?></td>
      <td><?= date('d.m.Y', strtotime($job['created_at'])) ?></td>
      <td>
        <a href="/public/admin/editJob/<?= $job['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
        <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="<?= $job['id'] ?>">Löschen</a>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
      <td colspan="6" class="text-center">Keine Stellenanzeigen vorhanden.</td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>
<!-- Modal für Löschbestätigung -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Stelle löschen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>
      <div class="modal-body">
        Möchten Sie diese Stelle wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
        <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Ja, löschen</a>
      </div>
    </div>
  </div>
</div>
<?php require_once 'footer.php'; ?>