<?php
session_start();

if ($_SESSION['loggedin'] !== true) {
  header("Location: ../index.php");
  exit;
}

if ($_SESSION['role'] === 'guru') {
  header("Location: guru.php");
  exit;
}

include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'];

  switch ($action) {
    case 'add':
      $nip = htmlspecialchars_decode($_POST['nip']);
      $nama = htmlspecialchars_decode($_POST['nama']);
      $password = hash("sha256", htmlspecialchars_decode($_POST['password']));
      $alamat = htmlspecialchars_decode($_POST['alamat']);
      $no_telp = htmlspecialchars_decode($_POST['no_telp']);
      $nip_admin = $_SESSION['nip'];

      $sql = 'INSERT INTO guru VALUES (?, ?, ?, ?, ?, ?)';
      $conn->execute_query($sql, [$nip, $nama, $password, $alamat, $no_telp, $nip_admin]);
      header("Location: admin.php");
      exit;
      break;

    case 'del':
      $nip = htmlspecialchars_decode($_POST['nip']);
      $sql = 'DELETE FROM guru WHERE nip = ?';
      $conn->execute_query($sql, [$nip]);
      break;
    
    default:
      break;
  }
}

// ambil semua nip dan nama guru
$guru_array = array();
$guru_sql = 'SELECT nip, nama, alamat, no_telp FROM guru';
$guru = $conn->execute_query($guru_sql);
if ($guru->num_rows > 0) {
  foreach ($guru as $row) {
    $guru_array[$row['nip']] = $row['nama'];
  }
}

// ambil data absensi
$date = '';
if (isset($_GET['date']))
  $date = $_GET['date'];
else
  $date = date('Y-m-d');

$absensi = array();
$absensi_sql = 'SELECT nip_guru, keterangan, catatan FROM absensi WHERE tanggal = ?';
$absensi = $conn->execute_query($absensi_sql, [$date]);

$guru_keterangan_array = array();
$guru_catatan_array = array();
if ($absensi->num_rows > 0) {
  foreach($absensi as $row) {
    $guru_keterangan_array[$row['nip_guru']] = $row['keterangan'];
    $guru_catatan_array[$row['nip_guru']] = $row['catatan'];
  }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin SIKG</title>
    <link href="../style.css" rel="stylesheet">
  </head>
  <body>
    <div class="topbar" style="background-color: magenta;">
      <table>
        <tr>
        <td rowspan="2"><img src="../logosmk.webp" alt="logo"></td>
        <td><h1>Selamat Pagi</h1><td>
        <td rowspan="2"><a class="btn" href="../login/logout.php">Log out</a></td>
        </tr>
        <tr>
        <td><p><?php echo $_SESSION['name'] . ' - ' . $_SESSION['jabatan']; ?></p></td>
        </tr>
      </table>
    </div>

    <h2>Tabel Absensi</h2>
    <form action="admin.php" method="GET">
      <label for="date">Tanggal</label>
      <input type="date" id="date" name="date" value="<?php echo $date; ?>">
      <input type="submit" value="Submit">
    </form>
    <table border="1">
      <thead>
        <td>NIP</td>
        <td>Nama</td>
        <td>Keterangan</td>
        <td>Catatan</td>
      </thead>
      <tbody>
      <?php
if ($absensi->num_rows > 0) {
  foreach ($guru_array as $nip => $nama) {
    echo '<tr>';
    echo '<td>' . $nip . '</td>';
    echo '<td>' . $nama . '</td>';
    echo '<td>';
    switch ($guru_keterangan_array[$nip]) {
      case 'H':
        echo 'Hadir';
        break;
      case 'I':
        echo 'Izin';
        break;
      case 'S':
        echo 'Sakit';
        break;
      default:
        echo 'Belum Mengisi';
        break;
    }
    echo '</td>';
    echo '<td>' . $guru_catatan_array[$nip] . '</td>';
    echo '</tr>';
  }
  echo '</tbody>';
} else {
  echo '<tfoot><td colspan="4">No data</td></tfoot>';
}
      ?>
    </table>

    <h2>Tabel Seluruh Guru</h2>
    <table border="1">
      <thead>
          <td>NIP</td>
          <td>Nama</td>
          <td>Alamat</td>
          <td>Nomor Telepon</td>
        </tr>
      </thead>
      <tbody>
      <?php
foreach ($guru as $row) {
  echo '<tr>';
  echo '<td>' . $row['nip'] . '</td>';
  echo '<td>' . $row['nama'] . '</td>';
  echo '<td>' . $row['alamat'] . '</td>';
  echo '<td>' . $row['no_telp'] . '</td>';
  echo '</tr>';
}
      ?>
      </tbody>
    </table>
    
    <h3>Tambah Guru</h3>
    <form action="admin.php" method="POST">
      <input type="number" id="nip" name="nip" placeholder="NIP" required><br>
      <input type="text" id="nama" name="nama" placeholder="Nama" required><br>
      <input type="password" id="password" name="password" placeholder="Password" required><br>
      <input type="text" id="alamat" name="alamat" placeholder="Alamat" required><br>
      <input type="number" id="no_telp" name="no_telp" placeholder="Nomor Telepon" required><br>
      <input style="display: none;" type="text" id="action" name="action" value="add" readonly>
      <input type="submit" value="Submit">
    </form>

    <h3>Hapus Guru</h3>
    <form action="admin.php" method="POST">
      <input type="number" id="nip" name="nip" placeholder="NIP" required><br>
      <input style="display: none;" type="text" id="action" name="action" value="del" readonly>
      <input type="submit" value="Submit">
    </form>
  </body>
</html>
