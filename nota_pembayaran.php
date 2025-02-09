<?php
session_start();
include 'db.php';

if (!isset($_GET['donasi_id'])) {
    die("Donasi ID tidak ditemukan.");
}

$donasi_id = $_GET['donasi_id'];

$query = mysqli_query($conn, "
    SELECT donasi.*, lembaga_sosial.nama_lembaga
    FROM donasi
    LEFT JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
    WHERE donasi.id = '$donasi_id'
");

$donasi = mysqli_fetch_assoc($query);

if (!$donasi) {
    die("Data donasi tidak ditemukan.");
}

$donatur_id = $_SESSION['user_id'];
$donatur_query = mysqli_query($conn, "SELECT nama FROM users WHERE id = '$donatur_id'");
$donatur_data = mysqli_fetch_assoc($donatur_query);
$nama_donatur = $donatur_data['nama'] ?? 'Anonim';

$status = trim(strtolower($donasi['status'])); 

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width: 500px;">
        <h2 class="text-center mb-4">Nota Pembayaran</h2>

        <table class="table">
            <tr>
                <th>Nama Donatur</th>
                <td><?= $nama_donatur; ?></td>
            </tr>
            <tr>
                <th>Lembaga Sosial</th>
                <td><?= $donasi['nama_lembaga']; ?></td>
            </tr>
            <tr>
                <th>Bank Tujuan</th>
                <td><?= $donasi['bank_tujuan']; ?></td>
            </tr>
            <tr>
                <th>No Rekening</th>
                <td><?= $donasi['no_rekening']; ?></td>
            </tr>
            <tr>
                <th>Nominal</th>
                <td>Rp <?= number_format($donasi['nominal'], 2, ',', '.'); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php 
                    if ($status === 'pending') {
                        echo '<span class="badge bg-warning">Pending</span>';
                    } elseif ($status === 'terima') {
                        echo '<span class="badge bg-success">Diterima</span>';
                    } else {
                        echo '<span class="badge bg-danger">Ditolak</span>';
                    }
                    ?>
                </td>
            </tr>
        </table>

        <a href="dashboard.php" class="btn btn-primary w-100 mt-3">Kembali ke Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'footer.php'; ?>
</body>
</html>
