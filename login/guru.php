<?php
session_start();

if ($_SESSION['loggedin'] === true) {
  if ($_SESSION['role'] === 'admin')
    header("Location: ../dash/admin.php");
  else
    header("Location: ../dash/guru.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nip = $_POST['nip'];
  $password = $_POST['password'];
  $password_hash = hash('sha256', $password);

  // koneksi mysql
  include '../koneksi.php';

  // cek jika ada nip dan password yang sama di database
  $guru_sql = 'SELECT * FROM guru WHERE nip = ? AND password_hash = ?';
  $guru = $conn->execute_query($guru_sql, [$nip, $password_hash]);

  // jika ada,
  if ($guru->num_rows > 0) {
    session_regenerate_id(); // session fixation attack prevention
    $guru_row = $guru->fetch_assoc(); // jadikan array yang bisa diakses dengan mudah
    $guru_nip = $guru_row['nip'];
    $guru_name = $guru_row['nama'];

    // session
    $_SESSION['loggedin'] = true;
    $_SESSION['role'] = 'guru';
    $_SESSION['nip'] = $guru_nip;
    $_SESSION['name'] = $guru_name;

    $conn->close();
    header("Location: ../dash/guru.php");
    exit;
  // jika tidak ada
  } else {
    $conn->close();
    header('Location: ' . $_SERVER['PHP_SELF'] . '?fail=1');
    exit;
  }
}

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login SIKG</title>
    <link href="../style.css" rel="stylesheet">
  </head>
  <body style="background-color: blue;">
    <div class="center">
      <div class="card">
        <img class="center-card-logo" src="../logosmk.webp" alt="Logo SMK" height="128">
        <h1 class="title">Sistem Kehadiran Guru</h1>
        <p class="center-card-text">Silakan login terlebih dahulu.</p>
          <?php if (isset($_GET['fail']) && $_GET['fail'] === '1') : ?>
            <p style="color: red;">NIP atau password salah. Silakan coba lagi.</p>
          <?php endif;?>
        <form action="guru.php" method="POST">
          <input type="text" id="nip" name="nip" placeholder="NIP" class="textfield" required>
          <input type="password" id="password" name="password" placeholder="Password" class="textfield" required>
          <div class="btn-container">
            <a class="btn" href="..">&lt; Kembali</a>
            <input class="btn" type="submit" value="Login Guru">
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
