<?php require_once 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2>👥 Benutzerverwaltung</h2>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<div class="mb-3">
  <input type="text" id="searchInput" class="form-control" placeholder="🔍 Benutzer suchen...">
</div>
<table class="table table-bordered table-hover align-middle">
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>E-Mail</th>
      <th>Admin?</th>
      <th>Registriert am</th>
      <th class="text-center">Aktionen</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($users) > 0): ?>
    <?php foreach ($users as $index => $user): ?>
    <tr>
      <td><?= $index + 1 ?></td>
      <td><?= htmlspecialchars($user['name']) ?></td>
      <td><?= htmlspecialchars($user['email']) ?></td>
      <td>
        <?= $user['is_admin'] ? '<span class="badge bg-secondary">Ja</span>' : '<span class="badge bg-light text-dark">Nein</span>' ?>
      </td>
      <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
      <td class="text-center">
        <?php if (!$user['is_admin']): ?>
        <a href="/public/admin/editUser/<?= $user['id'] ?>" class="btn btn-sm btn-warning">Bearbeiten</a>
        <a href="/public/admin/userDetails/<?= $user['id'] ?>" class="btn btn-sm btn-info">Details</a>
        <a href="/public/admin/promoteUser/<?= $user['id'] ?>" class="btn btn-sm btn-success"
          onclick="return confirm('Zum Admin befördern?');">Admin machen</a>
        <a href="#" class="btn btn-sm btn-danger delete-user-btn" data-id="<?= $user['id'] ?>">Löschen</a>
        <?php else: ?>
        <span class="text-muted">–</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php else: ?>
    <tr>
      <td colspan="6" class="text-center">Keine Benutzer gefunden.</td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>
<!-- Modal für Löschbestätigung -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">User löschen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Schließen"></button>
      </div>
      <div class="modal-body">
        Möchten Sie diese User wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
        <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Ja, löschen</a>
      </div>
    </div>
  </div>
</div>
<?php require_once 'footer.php'; ?>