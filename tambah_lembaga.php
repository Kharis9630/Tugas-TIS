<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['submit'])) {
    $nama_lembaga = mysqli_real_escape_string($conn, $_POST['nama_lembaga']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $insert_query = "INSERT INTO lembaga_sosial (nama_lembaga, email) VALUES ('$nama_lembaga', '$email')";
    if (mysqli_query($conn, $insert_query)) {
        $user_insert_query = "INSERT INTO users (nama, email, password, role) VALUES ('$nama_lembaga', '$email', '$password', 'lembaga')";
        mysqli_query($conn, $user_insert_query);
        
        echo "<script>alert('Lembaga Sosial berhasil ditambahkan!'); window.location='data_lembaga_sosial.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah lembaga sosial!');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Lembaga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Tambah Lembaga Sosial</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lembaga</label>
                            <input type="text" name="nama_lembaga" class="form-control" required>
                        </div>
                        <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" required>
</div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="submit" class="btn btn-primary">Tambah Lembaga</button>
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
