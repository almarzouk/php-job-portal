<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: /public/auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <title>Adminbereich</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Place the first <script> tag in your HTML's <head> -->
  <script src="https://cdn.tiny.cloud/1/82jm6afpjs7nlrh3ttmhxb8bzdlx4hr51ruyl0svgrpk356z/tinymce/7/tinymce.min.js"
    referrerpolicy="origin"></script>

  <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
  <script>
  tinymce.init({
    selector: 'textarea',
    plugins: [
      // Core editing features
      'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media',
      'searchreplace', 'table', 'visualblocks', 'wordcount',
      // Your account includes a free trial of TinyMCE premium features
      // Try the most popular premium features until May 29, 2025:
      'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker',
      'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions',
      'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss',
      'markdown', 'importword', 'exportword', 'exportpdf'
    ],
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [{
        value: 'First.Name',
        title: 'First Name'
      },
      {
        value: 'Email',
        title: 'Email'
      },
    ],
    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
      'See docs to implement AI Assistant')),
  });
  </script>
  <style>
  body {
    display: flex;
    min-height: 100vh;
    overflow: hidden;
  }

  .sidebar {
    width: 250px;
    background-color: #343a40;
    color: #fff;
    padding-top: 20px;
    flex-shrink: 0;
  }

  .sidebar a {
    color: #fff;
    text-decoration: none;
    padding: 12px 20px;
    display: block;
  }

  .sidebar a:hover {
    background-color: #495057;
  }

  .topbar {
    background-color: #f8f9fa;
    padding: 10px 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .content {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }

  .main {
    padding: 20px;
    overflow-y: auto;
    flex-grow: 1;
  }
  </style>
</head>

<body>
  <div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <hr class="bg-light">

    <a href="/public/admin/dashboard"><i class="bi bi-house-door me-2"></i>Dashboard</a>
    <a href="/public/admin/jobs"><i class="bi bi-card-text me-2"></i>Stellenanzeigen</a>
    <a href="/public/admin/addJob"><i class="bi bi-plus-square me-2"></i>Neue Stelle</a>
    <a href="/public/admin/users"><i class="bi bi-people me-2"></i>Benutzer</a>
    <a href="/public/admin/profile"><i class="bi bi-people me-2"></i>Profile</a>
    <a href="/public/admin/applications"><i class="bi bi-envelope-open me-2"></i>Bewerbungen</a>
    <a href="/public/admin/messages"><i class="bi bi-envelope-open me-2"></i>Nachrichten</a>

    <a href="/public/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Abmelden</a>
  </div>

  <div class="content">
    <div class="topbar">
      <span><i class="bi bi-person-circle me-1"></i>Willkommen, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
      <span class="text-muted small">Adminbereich</span>
    </div>

    <div class="main">