<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query = "AND (users.nama LIKE '%$search%' 
                        OR lembaga_sosial.nama_lembaga LIKE '%$search%' 
                        OR donasi.kategori LIKE '%$search%')";
}

$donasi_group_result = mysqli_query($conn, "
    SELECT donasi.lembaga_id, lembaga_sosial.nama_lembaga, donasi.kategori, SUM(donasi.nominal) AS total_nominal
    FROM donasi
    JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
    JOIN users ON donasi.donatur_id = users.id
    WHERE donasi.status = 'terima' $search_query
    GROUP BY donasi.lembaga_id, donasi.kategori
");

if (!$donasi_group_result) {
    die('Error pada query group: ' . mysqli_error($conn));
}

$donasi_all_result = mysqli_query($conn, "
    SELECT donasi.*, users.nama AS donatur, lembaga_sosial.nama_lembaga 
    FROM donasi
    JOIN users ON donasi.donatur_id = users.id
    JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
    WHERE donasi.status = 'terima' $search_query
");

if (!$donasi_all_result) {
    die('Error pada query all: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dana Masuk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

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


    <div class="container mt-4">

        <h2 class="text-center">Dana Masuk</h2>

        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Cari donatur, lembaga, atau kategori" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <?php if (mysqli_num_rows($donasi_group_result) > 0) { ?>
            <?php while ($group = mysqli_fetch_assoc($donasi_group_result)) { ?>
                <h3 class="mt-4">Donasi untuk Lembaga: <?= $group['nama_lembaga']; ?> (Kategori: <?= ucfirst($group['kategori']); ?>)</h3>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Donatur</th>
                            <th>Lembaga Sosial</th>
                            <th>Kategori</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total_nominal = 0;
                    $donasi_result = mysqli_query($conn, "
                        SELECT donasi.*, users.nama AS donatur, lembaga_sosial.nama_lembaga 
                        FROM donasi
                        JOIN users ON donasi.donatur_id = users.id
                        JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
                        WHERE donasi.lembaga_id = " . $group['lembaga_id'] . " 
                        AND donasi.kategori = '" . $group['kategori'] . "'
                        AND donasi.status = 'terima' $search_query
                    ");

                    while ($donasi = mysqli_fetch_assoc($donasi_result)) {
                        $total_nominal += $donasi['nominal'];
                    ?>
                    <tr>
                        <td><?= $donasi['donatur']; ?></td>
                        <td><?= $donasi['nama_lembaga']; ?></td>
                        <td><?= ucfirst($donasi['kategori']); ?></td>
                        <td>Rp <?= number_format($donasi['nominal'], 2, ',', '.'); ?></td>
                        <td><?= $donasi['created_at']; ?></td>
                    </tr>
                    <?php } ?>
                    <tr class="table-warning">
                        <td colspan="3" class="text-end"><strong>Total Nominal:</strong></td>
                        <td><strong>Rp <?= number_format($total_nominal, 2, ',', '.'); ?></strong></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            <?php } ?>
        <?php } else { ?>
            <p class="text-danger text-center"><strong>Tidak ada data yang cocok dengan pencarian.</strong></p>
        <?php } ?>

        <h3 class="mt-4">Semua Donasi Masuk</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Donatur</th>
                    <th>Lembaga Sosial</th>
                    <th>Kategori</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $total_all_nominal = 0;
            if (mysqli_num_rows($donasi_all_result) > 0) {
                while ($donasi = mysqli_fetch_assoc($donasi_all_result)) {
                    $total_all_nominal += $donasi['nominal'];
            ?>
            <tr>
                <td><?= $donasi['donatur']; ?></td>
                <td><?= $donasi['nama_lembaga']; ?></td>
                <td><?= ucfirst($donasi['kategori']); ?></td>
                <td>Rp <?= number_format($donasi['nominal'], 2, ',', '.'); ?></td>
                <td><?= $donasi['created_at']; ?></td>
            </tr>
            <?php } ?>
            <tr class="table-warning">
                <td colspan="3" class="text-end"><strong>Total Nominal Semua Donasi:</strong></td>
                <td><strong>Rp <?= number_format($total_all_nominal, 2, ',', '.'); ?></strong></td>
                <td></td>
            </tr>
            <?php } else { ?>
            <tr>
                <td colspan="5" class="text-center text-danger"><strong>Tidak ada data yang cocok dengan pencarian.</strong></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>

        <a href="admin.php" class="btn btn-secondary">Kembali ke Admin Panel</a>

    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
