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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nip = $_SESSION['nip'];
  $date = $_POST['date'];
  $keterangan = $_POST['keterangan'];
  $catatan = $_POST['catatan'];

  include '../koneksi.php';

  $sql = "INSERT INTO absensi (nip_guru, tanggal, keterangan, catatan) VALUES (?, ?, ?, ?)";
  $result = $conn->execute_query($sql, [$nip, $date, $keterangan, $catatan]);

  header("Location: guru.php?success=1");
  exit;
}

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Guru SIKG</title>
    <link href="../style.css" rel="stylesheet">
  </head>
  <body>
    <div class="topbar" style="background-color: blue;">
      <table>
        <tr>
        <td rowspan="2"><img src="../logosmk.webp" alt="logo"></td>
        <td><h1>Selamat Pagi</h1><td>
        <td rowspan="2"><a class="btn" href="../login/logout.php">Log out</a></td>
        </tr>
        <tr>
        <td><p><?php echo $_SESSION['name']; ?></p></td>
        </tr>
      </table>
    </div>

    <form action="guru.php" method="POST">
      <label for="date">Tanggal</label><br>
      <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" readonly><br>
      <label for="keterangan">Keterangan</label><br>
      <input type="radio" name="keterangan" id="keterangan" value="H" checked>Hadir<br>
      <input type="radio" name="keterangan" id="keterangan" value="I">Izin<br>
      <input type="radio" name="keterangan" id="keterangan" value="S">Sakit<br>
      <label for="catatan">Catatan (Opsional)</label><br>
      <input type="text" name="catatan" id="catatan"><br>
      <input type="submit" value="Submit">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?php if ($_GET['success']) : ?>
<script>
  Swal.fire({
    title: "Sukses",
    text: "Data absensi berhasil dicatat",
    icon: "success"
  });
</script>
    <?php endif; ?>
  </body>
</html>
