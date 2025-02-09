<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['edit_id']) || !is_numeric($_GET['edit_id'])) {
    header("Location: data_lembaga_sosial.php");
    exit();
}

$lembaga_id = htmlspecialchars($_GET['edit_id']);

$result = mysqli_query($conn, "SELECT lembaga_sosial.*, users.email 
    FROM lembaga_sosial 
    LEFT JOIN users ON lembaga_sosial.id = users.id 
    WHERE lembaga_sosial.id = '$lembaga_id'");

$lembaga = mysqli_fetch_assoc($result);

if (!$lembaga) {
    header("Location: data_lembaga_sosial.php");
    exit();
}

if (isset($_POST['submit'])) {
    $nama_lembaga = mysqli_real_escape_string($conn, $_POST['nama_lembaga']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $update_query = "UPDATE lembaga_sosial SET nama_lembaga = '$nama_lembaga' WHERE id = '$lembaga_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $update_user_query = "UPDATE users SET email = '$email' WHERE id = '$lembaga_id'";
        mysqli_query($conn, $update_user_query);

        echo "<script>alert('Lembaga Sosial berhasil diperbarui!'); window.location='data_lembaga_sosial.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui lembaga sosial!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Lembaga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-white text-center">
                    <h4>Edit Lembaga Sosial</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lembaga</label>
                            <input type="text" name="nama_lembaga" class="form-control" value="<?= htmlspecialchars($lembaga['nama_lembaga']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($lembaga['email']); ?>" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="submit" class="btn btn-warning">Simpan Perubahan</button>
                            <a href="data_lembaga_sosial.php" class="btn btn-secondary mt-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
