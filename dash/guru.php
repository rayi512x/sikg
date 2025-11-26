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

function savesession($date, $keterangan, $catatan) {
  $_SESSION['date'] = $date;
  $_SESSION['keterangan'] = $keterangan;
  $_SESSION['catatan'] = $catatan;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nip = $_SESSION['nip'];
  $date = $_POST['date'];
  $keterangan = $_POST['keterangan'];
  $catatan = $_POST['catatan'];

  savesession($date, $keterangan, $catatan);

  $sql_select = "SELECT id FROM absensi WHERE nip_guru = ? AND tanggal = ?";
  $result = $conn->execute_query($sql_select, [$nip, $date]);

  if ($result->num_rows === 0) {
    writedb(false, $conn);
  } else {
    $row = $result->fetch_assoc();
    $_SESSION['old_id'] = $row['id'];
    header("Location: guru.php?status=1");
    exit;
  }
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
        <td rowspan="2"><a class="btn" href="../login/logout.php">Logout</a></td>
        </tr>
        <tr>
        <td><p><?php echo $_SESSION['name']; ?></p></td>
        </tr>
      </table>
    </div>

    <div class="center-horizontally">
    <form action="guru.php" method="POST">
      <div class="input-container">
        <label for="date">Tanggal</label><br>
        <input type="date" id="date" name="date" class="textfield" style="width: 130px;" value="<?php echo date('Y-m-d'); ?>" readonly><br>
      </div>
      <div class="input-container">
        <label for="keterangan">Keterangan</label><br>
        <select name="keterangan" id="keterangan" class="textfield" style="width: 150px;">
          <option value="H">Hadir</option>
          <option value="I">Izin</option>
          <option value="S">Sakit</option>
        </select>
      </div>
      <div class="input-container">
      <label for="catatan">Catatan (Opsional)</label><br>
      <input type="text" name="catatan" class="textfield catatan" id="catatan"><br>
      </div>
      <div class="btn-container center-horizontally">
      <input type="submit" class="btn" value="Submit">
      </div>
    </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?php if ($_GET['status'] === '0') : ?>
<script>
  Swal.fire({
    title: "Sukses",
    text: "Data absensi berhasil dicatat",
    icon: "success"
  });
</script>
    <?php elseif ($_GET['status'] === '1') : ?>
    <script>
Swal.fire({
  text: "Data dengan NIP dan tanggal yang sama ditemukan dalam database. Perbarui?",
  icon: "info",
  showDenyButton: true,
  confirmButtonText: "Perbarui",
  denyButtonText: "Batal"
}).then((result) => {
  if (result.isConfirmed) {
    window.location.href = 'guru.php?status=2';
  } else if (result.isDenied) {
    Swal.fire("Perubahan tidak disimpan", "", "info");
  }
});
</script>
    <?php elseif ($_GET['status'] === '2') : writedb(true, $conn); endif; ?>
  </body>
</html>
