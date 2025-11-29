<?php
// start session
session_start();

if ($_SESSION['loggedin'] === true) {
  if ($_SESSION['role'] === 'admin')
    header("Location: ../dash/admin.php");
  else
    header("Location: ../dash/guru.php");
  exit;
}

// di eksekusi hanya jika menggunakan metode POST, setelah mengisi form.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // ambil data dari client
  $nip = $_POST['nip'];
  $password = $_POST['password'];
  $password_hash = hash('sha256', $password);

  // koneksi mysql
  include '../koneksi.php';

  // cek jika ada nip dan password yang sama di database
  $admin_sql = 'SELECT * FROM admin INNER JOIN jabatan ON admin.jabatan = jabatan.id WHERE nip = ? AND password_hash = ?';
  $admin = $conn->execute_query($admin_sql, [$nip, $password_hash]);

  // jika ada,
  if ($admin->num_rows > 0) {
    session_regenerate_id(); // session fixation attack prevention
    $admin_row = $admin->fetch_assoc(); // jadikan array yang bisa diakses dengan mudah
    $admin_nip = $admin_row['nip'];
    $admin_name = $admin_row['nama'];
    $admin_jabatan = $admin_row['title'];

    // session
    $_SESSION['loggedin'] = true;
    $_SESSION['role'] = 'admin';
    $_SESSION['nip'] = $admin_nip;
    $_SESSION['name'] = $admin_name;
    $_SESSION['jabatan'] = $admin_jabatan;

    $conn->close();
    header("Location: ../dash/admin.php");
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
  <body style="background-color: hotpink;">
    <div class="center">
      <div class="card">
        <img class="center-card-logo" src="../logosmk.webp" alt="Logo SMK" height="128">
        <h1 class="title">Sistem Kehadiran Guru</h1>
        <p class="center-card-text">Silakan login terlebih dahulu.</p>
          <?php if (isset($_GET['fail']) && $_GET['fail'] === '1') : ?>
            <p style="color: red;">NIP atau password salah. Silakan coba lagi.</p>
          <?php endif;?>
        <form action="admin.php" method="POST">
          <input type="text" id="nip" name="nip" placeholder="NIP" class="textfield" required><br>
          <input type="password" id="password" name="password" placeholder="Password" class="textfield" required>
          <div class="btn-container">
            <a class="btn" href="..">&lt; Kembali</a>
            <input class="btn" type="submit" value="Login Admin">
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
