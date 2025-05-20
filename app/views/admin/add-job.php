<?php
require_once 'header.php';

// معالجة البيانات عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // تحقق من صحة البيانات (يمكنك إضافة فحصات إضافية)
    if ($title && $location && $category && $description) {
        $jobModel = new Job();
        $result = $jobModel->createJob($title, $location, $category, $description);
        if ($result) {
            $_SESSION['success'] = 'Job erfolgreich hinzugefügt!';
            header('Location: /public/admin/jobs');
            exit;
        } else {
            $_SESSION['error'] = 'Fehler beim Hinzufügen des Jobs!';
        }
    } else {
        $_SESSION['error'] = 'Alle Felder müssen ausgefüllt werden!';
    }
}
?>

<div class="container mt-5">
  <h2>➕ Neue Stelle hinzufügen</h2>
  <hr>

  <!-- عرض الرسائل في حال كانت هناك أخطاء أو نجاح -->
  <?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger">
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
  </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
  </div>
  <?php endif; ?>

  <form action="/public/admin/addJob" method="POST">
    <div class="mb-3">
      <label for="title" class="form-label">Job Titel</label>
      <input type="text" name="title" id="title" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="location" class="form-label">Ort</label>
      <input type="text" name="location" id="location" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="category" class="form-label">Kategorie</label>
      <input type="text" name="category" id="category" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Beschreibung</label>
      <textarea name="description" id="description" class="form-control" rows="5"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Job hinzufügen</button>
  </form>
</div>

<?php require_once 'footer.php'; ?>