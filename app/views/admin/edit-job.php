<?php require_once 'header.php'; ?>

<div class="container mt-5">
  <h2>✏️ Stelle bearbeiten</h2>
  <hr>

  <form action="/public/admin/editJob/<?= $job['id'] ?>" method="POST">
    <div class="mb-3">
      <label for="title" class="form-label">Job Titel</label>
      <input type="text" name="title" id="title" class="form-control" required
        value="<?= htmlspecialchars($job['title']) ?>">
    </div>

    <div class="mb-3">
      <label for="location" class="form-label">Ort</label>
      <input type="text" name="location" id="location" class="form-control" required
        value="<?= htmlspecialchars($job['location']) ?>">
    </div>

    <div class="mb-3">
      <label for="category" class="form-label">Kategorie</label>
      <input type="text" name="category" id="category" class="form-control" required
        value="<?= htmlspecialchars($job['category']) ?>">
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Beschreibung</label>
      <textarea name="description" id="description" class="form-control"
        rows="5"><?= htmlspecialchars($job['description']) ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Änderungen speichern</button>
    <a href="/public/admin/jobs" class="btn btn-secondary">Abbrechen</a>
  </form>
</div>


<?php require_once 'footer.php'; ?>