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
    $search_query = "WHERE (lembaga_sosial.email LIKE '%$search%' 
                        OR lembaga_sosial.nama_lembaga LIKE '%$search%' 
                        OR laporan.deskripsi LIKE '%$search%')";
}

$laporan_result = mysqli_query($conn, "
    SELECT laporan.*, lembaga_sosial.email, lembaga_sosial.nama_lembaga 
    FROM laporan
    LEFT JOIN lembaga_sosial ON laporan.lembaga_id = lembaga_sosial.id
    $search_query
    ORDER BY lembaga_sosial.email ASC
");

if (!$laporan_result) {
    die('Error pada query laporan: ' . mysqli_error($conn));
}

$laporan_by_email = [];
while ($laporan = mysqli_fetch_assoc($laporan_result)) {
    $laporan_by_email[$laporan['email']][] = $laporan;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Donasi</title>
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
        <h2 class="text-center">Laporan Donasi</h2>
        
        <form method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari email, lembaga, atau deskripsi" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>

        <?php if (!empty($laporan_by_email)) { ?>
            <?php foreach ($laporan_by_email as $email => $laporan_list) { ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Laporan oleh: <?= htmlspecialchars($laporan_list[0]['nama_lembaga']) ?> (<?= htmlspecialchars($email); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID Laporan</th>
                                    <th>Tanggal Laporan</th>
                                    <th>Nominal Total</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total_nominal = 0; ?>
                                <?php foreach ($laporan_list as $laporan) { 
                                    $total_nominal += $laporan['nominal']; ?>
                                    <tr>
                                        <td><?= $laporan['id']; ?></td>
                                        <td><?= date("d-m-Y H:i", strtotime($laporan['created_at'])); ?></td>
                                        <td class="text-end">Rp <?= number_format($laporan['nominal'], 2, ',', '.'); ?></td>
                                        <td><?= htmlspecialchars($laporan['deskripsi']); ?></td>
                                    </tr>
                                <?php } ?>
                                <tr class="table-warning">
                                    <td colspan="2" class="text-end"><strong>Total Nominal:</strong></td>
                                    <td class="text-end"><strong>Rp <?= number_format($total_nominal, 2, ',', '.'); ?></strong></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-warning text-center">Tidak ada data yang cocok dengan pencarian.</div>
        <?php } ?>

        <div class="text-center mt-3">
            <a href="admin.php" class="btn btn-secondary">Kembali ke Admin Panel</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.php'; ?>
</body>
</html>
