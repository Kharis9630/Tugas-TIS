<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['edit_id']) || empty($_GET['edit_id'])) {
    header("Location: data_donatur.php");
    exit();
}

$edit_id = $_GET['edit_id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = '$edit_id' AND role = 'donatur'");
$donatur = mysqli_fetch_assoc($result);

if (!$donatur) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='data_donatur.php';</script>";
    exit();
}

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET nama='$nama', email='$email', password='$hashed_password' WHERE id='$edit_id' AND role='donatur'";
    } else {
        $update_query = "UPDATE users SET nama='$nama', email='$email' WHERE id='$edit_id' AND role='donatur'";
    }

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='data_donatur.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Donatur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Edit Donatur</h2>
    
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($donatur['nama']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($donatur['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password (Biarkan kosong jika tidak ingin diubah)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
        <a href="data_donatur.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>
