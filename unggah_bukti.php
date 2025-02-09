<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donatur') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['donasi_id'])) {
    header("Location: dashboard.php");
    exit();
}

$donasi_id = $_GET['donasi_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "uploads/"; 
    $file_name = basename($_FILES["bukti"]["name"]); 
    $target_file = $target_dir . $file_name; 

    if (file_exists($target_file)) {
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name = pathinfo($file_name, PATHINFO_FILENAME) . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;
    }

    if (move_uploaded_file($_FILES["bukti"]["tmp_name"], $target_file)) {
        $query = "UPDATE donasi SET bukti_transfer='$file_name' WHERE id='$donasi_id'";
        if (mysqli_query($conn, $query)) {
            header("Location: nota_pembayaran.php?donasi_id=$donasi_id");
            exit();
        } else {
            $error = "Gagal mengunggah bukti: " . mysqli_error($conn);
        }
    } else {
        $error = "Gagal mengunggah file.";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Bukti Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 400px;">
        <h2 class="text-center">Upload Bukti Transfer</h2>

        <?php if (isset($error)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="bukti" class="form-label">Pastikan nama file tidak mengandung karakter khusus dan spasi:</label>
                <input type="file" id="bukti" name="bukti" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Kirim Bukti</button>
        </form>

        <a href="dashboard.php" class="btn btn-secondary w-100 mt-3">Kembali ke Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'footer.php'; ?>
</body>
</html>
