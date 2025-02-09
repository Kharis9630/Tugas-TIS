<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['verifikasi']) && isset($_POST['donasi_id'])) {
    $donasi_id = $_POST['donasi_id'];

    $query = "UPDATE donasi SET status_verifikasi = 'Terverifikasi' WHERE id = $donasi_id";
    if (mysqli_query($conn, $query)) {
        header("Location: data_donasi_masuk.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
