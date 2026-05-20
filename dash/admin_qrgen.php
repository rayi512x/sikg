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

function getRandomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

$token = getRandomString(64);

$conn->execute_query("START TRANSACTION");

$sql_delete = "DELETE FROM secret";
$conn->execute_query($sql_delete);
$sql_insert = "INSERT INTO secret (token) VALUES (?)";
$conn->execute_query($sql_insert, [$token]);

$conn->execute_query("COMMIT");

$conn->close();

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode QR SIKG</title>
    <link href="../style.css" rel="stylesheet">
  </head>
  <body class="center" style="background-color: black;">
    <div style="text-align: center;">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=20&data=<?php echo $token; ?>" height="300px">
      <h1 style="margin: 50px; color: whitesmoke;">Kode QR Sistem Informasi Kehadiran Guru</h1>
      <p id="countdown" style="color: white;">30</p>
    </div>

<script>
let countdown = 30;
let display = document.getElementById('countdown');

setInterval(() => {
  countdown -= 1;
  if (countdown === 0) location.reload();
  display.innerHTML = countdown;
}, 1000);
</script>
  </body>
</html>
