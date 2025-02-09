<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $lembaga_id = $_GET['id'];

    $delete_query = "DELETE FROM lembaga_sosial WHERE id = '$lembaga_id'";

    if (mysqli_query($conn, $delete_query)) {
        header("Location: data_lembaga_sosial.php?success=deleted");
        exit();
    } else {
        echo "Gagal menghapus lembaga: " . mysqli_error($conn);
    }
} else {
    header("Location: data_lembaga_sosial.php");
    exit();
}
?>
