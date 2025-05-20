<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php require_once __DIR__ . '/../public/header.php'; ?>
<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <title>Mein Profil – JobMatch</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f1f5f9;
  }

  .job-container {
    max-width: 100%;
    margin-top: 120px;
  }

  .form-label {
    font-weight: 600;
  }

  .form-control {
    padding: 0.75rem 1rem;
    border-radius: 10px;
  }

  .btn-primary {
    background-color: #3b82f6;
    border: none;
    border-radius: 10px;
  }

  .btn-primary:hover {
    background-color: #2563eb;
  }

  .card {
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  }

  .alert {
    border-radius: 10px;
  }

  .section-title {
    font-weight: 700;
    font-size: 1.8rem;
    margin-bottom: 1rem;
  }
  </style>
</head>

<body>
  <div class="container">
    <div class="job-container">
      <div class="card p-4 bg-white shadow-sm">
        <div class="section-title mb-3"><i class="fas fa-user-circle me-2"></i>Mein Profil</div>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="/public/userprofile/update" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="name" class="form-label">Vollständiger Name</label>
            <input type="text" name="name" id="name" class="form-control" required
              value="<?= htmlspecialchars($user['name'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">E-Mail-Adresse</label>
            <input type="email" name="email" id="email" class="form-control" required
              value="<?= htmlspecialchars($user['email'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Neues Passwort <small class="text-muted">(optional)</small></label>
            <input type="password" name="password" id="password" class="form-control"
              placeholder="Leer lassen, um das Passwort nicht zu ändern">
          </div>

          <div class="mb-3">
            <label for="cv" class="form-label">Lebenslauf hochladen (PDF/DOC)</label>
            <input type="file" name="cv" id="cv" class="form-control" accept=".pdf,.doc,.docx">
            <?php if (!empty($user['cv'])): ?>
            <div class="mt-2">
              Aktueller CV:
              <a href="/public/uploads/<?= htmlspecialchars($user['cv']) ?>" target="_blank">Download</a>
            </div>
            <?php endif; ?>
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-3">Änderungen speichern</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>