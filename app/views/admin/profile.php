<?php include 'header.php'; ?>

<div class="container mt-5">
  <h2 class="mb-4"><i class="fas fa-user-cog me-2"></i> Mein Admin-Profil</h2>

  <?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>

  <form action="/public/admin/updateProfile" method="POST">
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" name="name" id="name" class="form-control" required
        value="<?= htmlspecialchars($admin['name']) ?>">
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">E-Mail-Adresse</label>
      <input type="email" name="email" id="email" class="form-control" required
        value="<?= htmlspecialchars($admin['email']) ?>">
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Neues Passwort <small class="text-muted">(optional)</small></label>
      <input type="password" name="password" id="password" class="form-control"
        placeholder="Leer lassen, um das Passwort nicht zu ändern">
    </div>

    <button type="submit" class="btn btn-primary w-100">Änderungen speichern</button>
  </form>
</div>

<?php include 'footer.php'; ?>