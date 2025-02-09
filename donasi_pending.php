<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['verifikasi_id'])) {
    $donasi_id = $_GET['verifikasi_id'];
    $update_query = "UPDATE donasi SET status = 'terima' WHERE id = '$donasi_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: donasi_pending.php");
        exit();
    } else {
        $error = "Gagal memperbarui status donasi: " . mysqli_error($conn);
    }
}

if (isset($_GET['tolak_id'])) {
    $donasi_id = $_GET['tolak_id'];
    $update_query = "UPDATE donasi SET status = 'batal' WHERE id = '$donasi_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: donasi_pending.php");
        exit();
    } else {
        $error = "Gagal mengubah status donasi menjadi batal: " . mysqli_error($conn);
    }
}

$donasi_result = mysqli_query($conn, "
    SELECT donasi.*, users.nama AS donatur, lembaga_sosial.nama_lembaga 
    FROM donasi
    JOIN users ON donasi.donatur_id = users.id
    JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
    WHERE donasi.status = 'pending'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Donasi Pending</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Donasi Online</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="admin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_donasi_masuk.php">Dana Masuk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_laporan.php">Laporan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_lembaga_sosial.php">Data Lembaga Sosial</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_donatur.php">Data Donatur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="donasi_pending.php">Donasi Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Donasi yang Belum Diverifikasi</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Donatur</th>
                <th>Lembaga Sosial</th>
                <th>Kategori</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Bukti Transfer</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($donasi = mysqli_fetch_assoc($donasi_result)): ?>
            <tr>
                <td><?= htmlspecialchars($donasi['donatur']); ?></td>
                <td><?= htmlspecialchars($donasi['nama_lembaga']); ?></td>
                <td><?= ucfirst(htmlspecialchars($donasi['kategori'])); ?></td>
                <td>Rp <?= number_format($donasi['nominal'], 2, ',', '.'); ?></td>
                <td><?= ucfirst(htmlspecialchars($donasi['status'])); ?></td>
                <td><?= htmlspecialchars($donasi['created_at']); ?></td>
                <td>
    <?php if (!empty($donasi['bukti_transfer'])): ?>
        <?php 
            $file_name = $donasi['bukti_transfer']; 
            $file_path = __DIR__ . "/uploads/" . $file_name; 
            $file_url = "uploads/" . urlencode($file_name); 
        ?>

        
        <?php if (file_exists($file_path)): ?>
            <img src="<?= htmlspecialchars($file_url); ?>" 
                 alt="Bukti Transfer" class="img-thumbnail" 
                 style="max-width: 150px;">
        <?php else: ?>
            <span class="text-danger">File tidak ditemukan</span>
        <?php endif; ?>
    <?php else: ?>
        <span class="text-danger">Belum diunggah</span>
    <?php endif; ?>
</td>


                <td>
                    <a href="donasi_pending.php?verifikasi_id=<?= $donasi['id']; ?>" 
                       class="btn btn-success btn-sm">✔ Verifikasi</a>
                    <a href="donasi_pending.php?tolak_id=<?= $donasi['id']; ?>" 
                       class="btn btn-warning btn-sm" 
                       onclick="return confirm('Apakah Anda yakin ingin membatalkan donasi ini?');">🚫 Batal</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center">
        <a href="admin.php" class="btn btn-secondary">⬅ Kembali ke Admin Panel</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
