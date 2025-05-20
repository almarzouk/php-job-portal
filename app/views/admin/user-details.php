<?php require_once 'header.php'; ?>

<div class="container mt-5">
  <h2>👁 Benutzerdetails</h2>
  <hr>

  <table class="table table-bordered w-50">
    <tr>
      <th>Name</th>
      <td><?= htmlspecialchars($user['name']) ?></td>
    </tr>
    <tr>
      <th>E-Mail</th>
      <td><?= htmlspecialchars($user['email']) ?></td>
    </tr>
    <tr>
      <th>Admin?</th>
      <td><?= $user['is_admin'] ? 'Ja' : 'Nein' ?></td>
    </tr>
    <tr>
      <th>Registriert am</th>
      <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
    </tr>
  </table>

  <a href="/public/admin/editUser/<?= $user['id'] ?>" class="btn btn-warning">Bearbeiten</a>
  <a href="/public/admin/users" class="btn btn-secondary">Zurück</a>
</div>

<?php require_once 'footer.php'; ?>