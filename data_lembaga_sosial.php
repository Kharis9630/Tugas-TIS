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
    $search_query = "AND (nama_lembaga LIKE '%$search%' OR email LIKE '%$search%')";
}

$lembaga_result = mysqli_query($conn, "
    SELECT * FROM lembaga_sosial 
    WHERE 1 $search_query 
    ORDER BY nama_lembaga ASC
");

if (!$lembaga_result) {
    die('Error pada query: ' . mysqli_error($conn));
}

if (isset($_GET['hapus_id'])) {
    $hapus_id = $_GET['hapus_id'];
    $hapus_query = "DELETE FROM lembaga_sosial WHERE id = '$hapus_id'";

    if (mysqli_query($conn, $hapus_query)) {
        header("Location: data_lembaga_sosial.php");
        exit();
    } else {
        $error = "Gagal menghapus lembaga sosial: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Lembaga Sosial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmDelete(id) {
            document.getElementById('hapusLink').href = "data_lembaga_sosial.php?hapus_id=" + id;
            new bootstrap.Modal(document.getElementById('hapusModal')).show();
        }
    </script>
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
    <h2 class="mb-4 text-center">Data Lembaga Sosial</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mb-3">
        <a href="tambah_lembaga.php" class="btn btn-success">+ Tambah Lembaga Sosial</a>

        <form method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari nama atau email" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>

    <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
            <tr>
                <th>Nama Lembaga</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($lembaga_result) > 0) { ?>
                <?php while ($lembaga = mysqli_fetch_assoc($lembaga_result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($lembaga['nama_lembaga']); ?></td>
                    <td><?= htmlspecialchars($lembaga['email']); ?></td>
                    <td>
                        <a href="edit_lembaga.php?edit_id=<?= $lembaga['id']; ?>" class="btn btn-warning btn-sm">✏ Edit</a>
                        <button onclick="confirmDelete(<?= $lembaga['id']; ?>)" class="btn btn-danger btn-sm">🗑 Hapus</button>
                    </td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="3" class="text-danger text-center">Tidak ada data yang cocok dengan pencarian.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="text-center">
        <a href="admin.php" class="btn btn-secondary">⬅ Kembali ke Admin Panel</a>
    </div>
</div>

<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus lembaga sosial ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a id="hapusLink" href="#" class="btn btn-danger">Ya, Hapus</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
