<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white px-3 ms-2" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">Admin Panel - Kelola Data</h2>

                <div class="list-group">
                    <a href="data_donasi_masuk.php" class="list-group-item list-group-item-action">📥 Dana Masuk</a>
                    <a href="data_laporan.php" class="list-group-item list-group-item-action">📊 Laporan</a>
                    <a href="data_lembaga_sosial.php" class="list-group-item list-group-item-action">🏢 Data Lembaga Sosial</a>
                    <a href="data_donatur.php" class="list-group-item list-group-item-action">🙋 Data Donatur</a>
                    <a href="donasi_pending.php" class="list-group-item list-group-item-action">⏳ Donasi Pending</a>
                </div>

                <hr>

                <div class="text-center">
                    <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
