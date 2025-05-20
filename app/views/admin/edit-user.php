<?php require_once 'header.php'; ?>

<div class="container mt-5">
  <h2>✏️ Benutzer bearbeiten</h2>
  <hr>

  <form action="/public/admin/editUser/<?= $user['id'] ?>" method="POST">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">E-Mail</label>
      <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>">
    </div>

    <button type="submit" class="btn btn-primary">Speichern</button>
    <a href="/public/admin/users" class="btn btn-secondary">Zurück</a>
  </form>
</div>

<?php require_once 'footer.php'; ?>