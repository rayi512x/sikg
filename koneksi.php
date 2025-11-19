<?php

include 'dbcreds.php';

$conn = new mysqli('localhost', $db_user, $db_password, 'sikg');

if ($conn->connect_error) {
  die('Connection error: ' . $conn->connect_error);
}

?>
