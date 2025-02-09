<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lembaga') {
    header("Location: login.php");
    exit();
}

$lembaga_id = $_SESSION['user_id'];

$query_lembaga = "SELECT nama FROM users WHERE id = '$lembaga_id'";
$result = mysqli_query($conn, $query_lembaga);
$data = mysqli_fetch_assoc($result);
$nama_lembaga = $data['nama'] ?? 'Lembaga';

$query_lembaga_id = "SELECT id FROM lembaga_sosial WHERE nama_lembaga = '$nama_lembaga'";
$result_lembaga_id = mysqli_query($conn, $query_lembaga_id);
$data_lembaga_id = mysqli_fetch_assoc($result_lembaga_id);
$lembaga_id_sosial = $data_lembaga_id['id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $nominal = (int) $_POST['nominal'];

    if ($nominal > 0 && $lembaga_id_sosial) {
        $query = "INSERT INTO laporan (lembaga_id, nama_lembaga, deskripsi, nominal) 
                  VALUES ('$lembaga_id_sosial', '$nama_lembaga', '$deskripsi', '$nominal')";

        if (mysqli_query($conn, $query)) {
            $success = "Laporan berhasil dikirim.";
        } else {
            $error = "Gagal mengirim laporan: " . mysqli_error($conn);
        }
    } else {
        $error = "Nominal harus lebih dari 0 dan lembaga harus valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penggunaan Dana</title>
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
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white px-3 ms-2" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow p-4">
                <h2 class="text-center">Laporan Penggunaan Dana</h2>
                <p class="text-center">Lembaga: <strong><?php echo htmlspecialchars($nama_lembaga); ?></strong></p>

                <?php if (isset($success)) { ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php } elseif (isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Penggunaan Dana:</label>
                        <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nominal Dana yang Digunakan (Rp):</label>
                        <input type="number" name="nominal" class="form-control" min="1" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Kirim Laporan</button>
                </form>

                <hr>
                <div class="text-center">
                    <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
