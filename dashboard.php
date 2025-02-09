<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$query_user = mysqli_query($conn, "SELECT nama FROM users WHERE id = '$user_id'");
$user_data = mysqli_fetch_assoc($query_user);
$nama_user = $user_data['nama'] ?? 'Pengguna';

$notifikasi = "";
$donasi_data = [];

if ($role == "donatur") {
    $query_donasi = mysqli_query($conn, "SELECT * FROM donasi WHERE donatur_id = '$user_id' ORDER BY created_at DESC");

    if (!$query_donasi) {
        die("Error dalam query: " . mysqli_error($conn)); 
    }

    $donasi_data = mysqli_fetch_all($query_donasi, MYSQLI_ASSOC);

    foreach ($donasi_data as $donasi) {
        if ($donasi['status'] === 'Diterima') {
            $notifikasi = "<div class='alert alert-success'>Donasi sebesar Rp " . number_format($donasi['nominal'], 2, ',', '.') . " telah diterima.</div>";
            break;
        } elseif ($donasi['status'] === 'Ditolak') {
            $notifikasi = "<div class='alert alert-danger'>Donasi sebesar Rp " . number_format($donasi['nominal'], 2, ',', '.') . " ditolak.</div>";
            break;
        } elseif ($donasi['status'] === 'Pending') {
            $notifikasi = "<div class='alert alert-warning'>Donasi sebesar Rp " . number_format($donasi['nominal'], 2, ',', '.') . " sedang menunggu verifikasi.</div>";
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Sistem Donasi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($role == "donatur") { ?>
                    <li class="nav-item"><a class="nav-link" href="donasi.php">Buat Donasi</a></li>
                <?php } elseif ($role == "lembaga") { ?>
                    <li class="nav-item"><a class="nav-link" href="laporan.php">Buat Laporan Dana</a></li>
                <?php } elseif ($role == "admin") { ?>
                    <li class="nav-item"><a class="nav-link" href="admin.php">Panel Admin</a></li>
                <?php } ?>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white px-3 ms-2" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow p-4">
                <h2 class="text-center">Dashboard</h2>
                <p class="text-center">Selamat datang, <strong><?php echo htmlspecialchars($nama_user); ?></strong>!</p>

                <?php if ($role == "donatur" && !empty($notifikasi)) echo $notifikasi; ?>

                <?php if ($role == "donatur") { ?>
                    <h4 class="mt-4">Riwayat Donasi Anda</h4>
                    <table class="table table-bordered mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($donasi_data) > 0) {
                                $no = 1;
                                foreach ($donasi_data as $donasi) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>Rp " . number_format($donasi['nominal'], 2, ',', '.') . "</td>";
                                    echo "<td>" . ucfirst($donasi['status']) . "</td>";
                                    echo "<td>" . date('d-m-Y H:i', strtotime($donasi['created_at'])) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>Belum ada donasi.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                <?php } ?>

                <div class="d-grid gap-2">
                    <?php if ($role == "donatur") { ?>
                        <a href="donasi.php" class="btn btn-primary">Buat Donasi</a>
                    <?php } elseif ($role == "lembaga") { ?>
                        <a href="laporan.php" class="btn btn-primary">Buat Laporan Dana</a>
                    <?php } elseif ($role == "admin") { ?>
                        <a href="admin.php" class="btn btn-primary">Panel Admin</a>
                    <?php } ?>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
