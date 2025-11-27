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
      header("Location: admin.php?status=0");
      exit;
      break;

    case 'del':
      $nip = htmlspecialchars_decode($_POST['nip']);
      $sql = 'DELETE FROM guru WHERE nip = ?';
      $conn->execute_query($sql, [$nip]);
      header("Location: admin.php?status=1");
      exit;
      break;
    
    default:
      break;
  }
}

// ambil semua nip dan nama guru
$guru_array = array();
$guru_sql = 'SELECT nip, nama, alamat, no_telp FROM guru ORDER BY nama';
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

    <div class="center-horizontally">
    <div class="table-container">
    <h2>Tabel Absensi</h2>
    <form action="admin.php" method="GET">
      <label for="date">Tanggal:</label>
      <input type="date" id="date" name="date" class="textfield" value="<?php echo $date; ?>">
      <input type="submit" class="btn" value="Ganti tangal">
      <a class="btn" href="admin_qrgen.php">Panel kode QR</a><br>
      <input type="text" placeholder="Cari dari nama..." class="textfield table-filter" data-table="absensi" style="margin-bottom: 5px;">
    </form>
    <table border="1" class="absensi">
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
    </div>

    <div class="table-container">
    <h2>Tabel Seluruh Guru</h2>

      <div class="btn-container">
        <button onclick="displayTambahGuru()" class="btn">Tambah Guru</button>
        <button onclick="displayHapusGuru()" class="btn">Hapus Guru</button>
        <input type="text" placeholder="Cari dari nama..." class="textfield table-filter" data-table="tabel-data-guru">
      </div>

      <div class="form-container" id="form-tambah-guru" <?php if ($_GET['status'] === '0') echo 'style="display: grid;"'; else echo 'style="display: none;"'; ?>>
<?php if ($_GET['status'] === '0') echo '<p>Berhasil menambahkan guru</p>'; ?>
    <h3>Tambah Guru</h3>
    <form action="admin.php" method="POST">
      <input type="number" id="nip" name="nip" placeholder="NIP" class="textfield" required><br>
      <input type="text" id="nama" name="nama" placeholder="Nama" class="textfield" required><br>
      <input type="password" id="password" name="password" placeholder="Password" class="textfield" required><br>
      <input type="text" id="alamat" name="alamat" placeholder="Alamat" class="textfield" required><br>
      <input type="number" id="no_telp" name="no_telp" placeholder="Nomor Telepon" class="textfield" required>
      <input type="hidden" id="action" name="action" value="add" readonly>
      <input type="submit" class="btn" value="Tambah">
    </form>
    </div>

    <div class="form-container" id="form-hapus-guru" <?php if ($_GET['status'] === '1') echo 'style="display: grid;"'; else echo 'style="display: none;"'; ?>>
<?php if ($_GET['status'] === '1') echo '<p>Berhasil menghapus guru</p>'; ?>
    <h3>Hapus Guru</h3>
    <form action="admin.php" method="POST">
      <input type="number" id="nip" name="nip" placeholder="NIP" class="textfield" required>
      <input type="hidden" id="action" name="action" value="del" readonly>
      <input type="submit" value="Hapus" class="btn">
    </form>
    </div>

    <table border="1" class="tabel-data-guru">
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
      </div>
    
    </div>

<script>
function displayForm(form, form_lain) {
  if (form.style.display === 'none') {
    form.style.display = 'grid';
    if (form_lain.style.display !== 'none') {
      form_lain.style.display = 'none';
    }
  } else {
    form.style.display = 'none';
  }
}

function displayTambahGuru() {
  let form = document.getElementById("form-tambah-guru");
  let form_lain = document.getElementById("form-hapus-guru");

  displayForm(form, form_lain);
}

function displayHapusGuru() {
  let form = document.getElementById("form-hapus-guru");
  let form_lain = document.getElementById("form-tambah-guru");

  displayForm(form, form_lain);
}

(function() {
  'use strict';

  var TableFilter = (function() {
    var Arr = Array.prototype;
    var input;

    function onInputEvent(e) {
      input = e.target;
      var table1 = document.getElementsByClassName(input.getAttribute('data-table'));
      Arr.forEach.call(table1, function(table) {
        Arr.forEach.call(table.tBodies, function(tbody) {
          Arr.forEach.call(tbody.rows, filter);
        });
      });
    }

    function filter(row) {
      var text = row.textContent.toLowerCase();
      var val = input.value.toLowerCase();
      row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
    }

    return {
      init: function() {
        var inputs = document.getElementsByClassName('table-filter');
        Arr.forEach.call(inputs, function(input) {
          input.oninput = onInputEvent;
        });
      }
    };

  })();

 TableFilter.init();
})();
</script>

  </body>
</html>
