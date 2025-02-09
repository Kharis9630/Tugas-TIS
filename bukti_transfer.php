<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donatur') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['donasi_id'])) {
    die("Donasi ID tidak ditemukan.");
}

$donasi_id = $_GET['donasi_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['bukti_transfer'])) {
    $target_dir = "uploads/";
    $file_name = basename($_FILES["bukti_transfer"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $target_file)) {
        $query = "UPDATE donasi SET bukti_transfer='$file_name', status='confirmed' WHERE id='$donasi_id'";
        mysqli_query($conn, $query);
        header("Location: nota_pembayaran.php?donasi_id=$donasi_id");
        exit();
    } else {
        $error = "Gagal mengunggah bukti.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Upload Bukti Transfer</title>
</head>
<body>
    <h2>Upload Bukti Transfer</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Unggah Bukti Transfer:</label>
        <input type="file" name="bukti_transfer" required><br>
        <button type="submit">Kirim Bukti</button>
    </form>

    <br>
    <a href="dashboard.php">Kembali ke Dashboard</a>
    <?php include 'footer.php'; ?>
</body>
</html>
