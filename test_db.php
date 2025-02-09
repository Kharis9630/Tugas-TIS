<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_donasi";

$conn = mysqli_connect($host, $user, $pass, $db);

if ($conn) {
    echo "Koneksi ke database berhasil!";
} else {
    echo "Error: " . mysqli_connect_error();
}
?>
