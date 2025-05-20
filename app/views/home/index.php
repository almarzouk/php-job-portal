<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /public/auth/login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <title>Startseite</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <style>
  body {
    background: linear-gradient(to right, #e3f2fd, #fff);
    min-height: 100vh;
  }

  .welcome-card {
    border: none;
    border-radius: 1rem;
    background-color: #ffffff;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
  }

  .logout-btn {
    position: absolute;
    top: 20px;
    right: 20px;
  }

  .icon {
    font-size: 4rem;
    color: #0d6efd;
  }
  </style>
</head>

<body>

  <div class="container py-5">
    <div class="logout-btn">
      <a href="/public/auth/logout" class="btn btn-outline-danger">Abmelden</a>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card p-4 text-center welcome-card">
          <div class="mb-4">
            <div class="icon">👋</div>
          </div>
          <h2 class="mb-3">Willkommen zurück, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
          <p class="lead">Sie sind jetzt erfolgreich angemeldet. Nutzen Sie die Plattform, um passende Ausbildungs- und
            Weiterbildungsstellen zu finden.</p>
        </div>
      </div>
    </div>
  </div>

</body>

</html>