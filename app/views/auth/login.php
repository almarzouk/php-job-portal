<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <title>JobMatch – Anmeldung</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #3b82f6, #60a5fa);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .login-box {
    background: white;
    border-radius: 16px;
    padding: 2.5rem;
    max-width: 480px;
    width: 100%;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.4s ease-in-out;
  }

  @keyframes fadeIn {
    from {
      transform: translateY(20px);
      opacity: 0;
    }

    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  .login-title {
    font-weight: 700;
    font-size: 1.8rem;
    text-align: center;
    margin-bottom: 1rem;
    color: #1e293b;
  }

  .form-label {
    font-weight: 500;
    color: #334155;
  }

  .btn-primary {
    background-color: #3b82f6;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    padding: 0.6rem 1.5rem;
  }

  .btn-primary:hover {
    background-color: #2563eb;
  }

  .form-control {
    border-radius: 12px;
  }

  .text-link {
    display: block;
    margin-top: 1.25rem;
    text-align: center;
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
  }

  .text-link:hover {
    text-decoration: underline;
  }

  .alert {
    border-radius: 12px;
  }
  </style>
</head>

<body>

  <div class="login-box">
    <div class="login-title">Anmeldung bei JobMatch</div>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form action="/public/auth/doLogin" method="POST" id="loginForm">
      <div class="mb-3">
        <label for="email" class="form-label">E-Mail-Adresse</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Passwort</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Einloggen</button>
    </form>

    <a href="/public/auth/register" class="text-link">Noch kein Konto? Jetzt registrieren.</a>
  </div>

  <!-- jQuery + حفظ القيم مؤقتاً -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
  $('#loginForm input').on('input', function() {
    localStorage.setItem($(this).attr('id'), $(this).val());
  });

  $(document).ready(function() {
    $('#loginForm input').each(function() {
      const value = localStorage.getItem($(this).attr('id'));
      if (value) $(this).val(value);
    });
  });

  <?php if (isset($_SESSION['user_id'])): ?>
  localStorage.removeItem('email');
  localStorage.removeItem('password');
  <?php endif; ?>
  </script>

</body>

</html>