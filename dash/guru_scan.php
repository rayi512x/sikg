<?php
session_start();

if ($_SESSION['loggedin'] !== true) {
  header("Location: ../index.php");
  exit;
}

if ($_SESSION['role'] === 'admin') {
  header("Location: admin.php");
  exit;
}

include '../koneksi.php';

function writedb($update, $conn) {
  if ($update === true) {
    $sql_update = "UPDATE absensi SET keterangan = ?, catatan = ? WHERE id = ?";
    $conn->execute_query($sql_update, [$_SESSION['keterangan'], $_SESSION['catatan'], $_SESSION['old_id']]);
  } else {
    $sql_insert = "INSERT INTO absensi (nip_guru, tanggal, keterangan, catatan) VALUES (?, ?, ?, ?)";
    $conn->execute_query($sql_insert, [$_SESSION['nip'], $_SESSION['date'], $_SESSION['keterangan'], $_SESSION['catatan']]);
  }

  header("Location: guru.php?status=0");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql_select = "SELECT token FROM secret";
  $result = $conn->execute_query($sql_select);
  $row = $result->fetch_assoc();

  if ($_POST['token'] === $row['token']) {
    if ($_POST['update'] === '1') {
      writedb(true, $conn);
    } else {
      writedb(false, $conn);
    }
  } else {
    if ($_POST['update'] === '1') {
      header("Location: guru_scan.php?fail=1&update=1");
    } else {
      header("Location: guru_scan.php?fail=1");
    }
  }
}

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scan QR SIKG</title>
    <link href="../style.css" rel="stylesheet">
  </head>
  <body>
    <div class="topbar" style="background-color: blue;">
      <table>
        <tr>
        <td rowspan="2"><img src="../logosmk.webp" alt="logo"></td>
        <td><h1>Selamat Pagi</h1><td>
        <td rowspan="2"><a class="btn" href="../login/logout.php">Logout</a></td>
        </tr>
        <tr>
        <td><p><?php echo $_SESSION['name']; ?></p></td>
        </tr>
      </table>
    </div>

    <div class="center-horizontally">
    <h2>Scan kode QR</h2>
    <?php if ($_GET['fail'] === '1') : ?>
      <p style="color: red;">Mohon coba lagi</p>
    <?php endif; ?>
    </div>

    <div id="reader" width="600px"></div>

    <form id="form" action="guru_scan.php" method="POST">
      <input id="token" name="token" type="hidden">
      <input id="update" name="update" type="hidden" value="0">
    </form>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
const html5QrCode = new Html5Qrcode("reader");
const qrCodeSuccessCallback = (decodedText, decodedResult) => {
    /* handle success */
  document.getElementById('token').value = decodedText;
<?php if ($_GET['update'] === '1') : ?>
  document.getElementById('update').value = 1;
<?php endif; ?>
  document.getElementById('form').submit();
};
const config = { fps: 10, qrbox: { width: 250, height: 250 } };

// If you want to prefer back camera
html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
</script>
  </body>
</html>
