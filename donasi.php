<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donatur') {
    header("Location: login.php");
    exit();
}

$donatur_id = $_SESSION['user_id'];
$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lembaga_id = $_POST['lembaga_id'];
    $kategori = $_POST['kategori'];
    $nominal = $_POST['nominal'];
    $bank_tujuan = $_POST['bank_tujuan'];
    $no_rekening = $_POST['no_rekening'];

    if ($nominal < 10000) {
        $error = "Nominal donasi tidak boleh kurang dari Rp10.000.";
    } elseif ($nominal < 0) {
        $error = "Nominal donasi tidak boleh negatif.";
    } else {
        $query = "INSERT INTO donasi (donatur_id, lembaga_id, kategori, nominal, status, bank_tujuan, no_rekening) 
                  VALUES (?, ?, ?, ?, 'pending', ?, ?)";

        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, 'iissss', $donatur_id, $lembaga_id, $kategori, $nominal, $bank_tujuan, $no_rekening);

            if (mysqli_stmt_execute($stmt)) {
                $donasi_id = mysqli_insert_id($conn);
                header("Location: unggah_bukti.php?donasi_id=$donasi_id");
                exit();
            } else {
                $error = "Gagal melakukan donasi: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Gagal mempersiapkan query: " . mysqli_error($conn);
        }
    }
}

$lembaga_result = mysqli_query($conn, "SELECT * FROM lembaga_sosial");

if (!$lembaga_result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Formulir Donasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function updateRekening() {
            var bank = document.getElementById("bank_tujuan").value;
            var rekening = {
                "BCA": "123-456-7890",
                "Mandiri": "987-654-3210",
                "BRI": "555-666-777",
                "BNI": "222-333-444"
            };
            document.getElementById("no_rekening").value = rekening[bank];
        }

        function validateForm(event) {
            var nominal = document.getElementById("nominal").value;
            var errorDiv = document.getElementById("error-message");

            if (nominal < 10000) {
                errorDiv.innerHTML = "Nominal donasi tidak boleh kurang dari Rp10.000.";
                errorDiv.style.display = "block";
                event.preventDefault(); 
                return false;
            }

            if (nominal < 0) {
                errorDiv.innerHTML = "Nominal donasi tidak boleh negatif.";
                errorDiv.style.display = "block";
                event.preventDefault();
                return false;
            }

            errorDiv.style.display = "none"; 
            return true;
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Formulir Donasi</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

                        <div id="error-message" class="alert alert-danger" style="display: none;"></div>

                        <form method="POST" onsubmit="return validateForm(event)">
                            <div class="mb-3">
                                <label for="lembaga_id" class="form-label">Pilih Lembaga Sosial:</label>
                                <select name="lembaga_id" id="lembaga_id" class="form-select" required>
                                    <?php if (mysqli_num_rows($lembaga_result) > 0) { ?>
                                        <?php while ($lembaga = mysqli_fetch_assoc($lembaga_result)) { ?>
                                            <option value="<?= $lembaga['id']; ?>"><?= $lembaga['nama_lembaga']; ?></option>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <option value="">Belum ada lembaga sosial tersedia</option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori Donasi:</label>
                                <select name="kategori" id="kategori" class="form-select" required>
                                    <option value="pendidikan">Pendidikan</option>
                                    <option value="kesehatan">Kesehatan</option>
                                    <option value="bencana">Bencana</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="nominal" class="form-label">Nominal (Rp):</label>
                                <input type="number" name="nominal" id="nominal" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="bank_tujuan" class="form-label">Pilih Bank Tujuan:</label>
                                <select name="bank_tujuan" id="bank_tujuan" class="form-select" required onchange="updateRekening()">
                                    <option value="BCA">BCA</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="BRI">BRI</option>
                                    <option value="BNI">BNI</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="no_rekening" class="form-label">Nomor Rekening:</label>
                                <input type="text" id="no_rekening" name="no_rekening" class="form-control" readonly>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Lanjutkan Pembayaran</button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
