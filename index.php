<?php
session_start();

if ($_SESSION['loggedin'] === true) {
  if ($_SESSION['role'] === 'admin')
    header("Location: dash/admin.php");
  else
    header("Location: dash/guru.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login SIKG</title>
    <link href="style.css" rel="stylesheet">
  </head>
  <body style="background-color: #aaa;">
    <div class="center">
      <div class="card">
        <img class="center-card-logo" src="logosmk.webp" alt="Logo SMK" height="128">
        <h1 class="title">Sistem Kehadiran Guru</h1>
          <?php if ($_GET['logout'] === '1') : ?>
            <p style="color: green;">Berhasil logout.</p>
          <?php endif; ?>
        <p class="center-card-text">Silakan pilih:</p>
        <div class="btn-container">
          <a class="btn" href="login/guru.php">Guru</a>
          <a class="btn" href="login/admin.php">Admin</a>
        </div>
      </div>
    </div>
  </body>
</html>
