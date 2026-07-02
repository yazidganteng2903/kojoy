<?php
session_start();
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = $conn->query("SELECT * FROM jobs WHERE id = $id")->fetch_assoc();

if (!$data) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Lowongan tidak ditemukan!'];
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul_posisi      = $conn->real_escape_string($_POST['judul_posisi']);
    $nama_perusahaan   = $conn->real_escape_string($_POST['nama_perusahaan']);
    $tipe_pekerjaan    = $conn->real_escape_string($_POST['tipe_pekerjaan']);
    $gaji              = $conn->real_escape_string($_POST['gaji']);
    $deadline          = $conn->real_escape_string($_POST['deadline']);
    $deskripsi         = $conn->real_escape_string($_POST['deskripsi']);
    $persyaratan       = $conn->real_escape_string($_POST['persyaratan']);
    $profil_perusahaan = $conn->real_escape_string($_POST['profil_perusahaan']);

    $sql = "UPDATE jobs SET
            judul_posisi = '$judul_posisi',
            nama_perusahaan = '$nama_perusahaan',
            tipe_pekerjaan = '$tipe_pekerjaan',
            gaji = '$gaji',
            deadline = '$deadline',
            deskripsi = '$deskripsi',
            persyaratan = '$persyaratan',
            profil_perusahaan = '$profil_perusahaan'
            WHERE id = $id";

    if ($conn->query($sql)) {
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Lowongan berhasil diperbarui!'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal memperbarui lowongan: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lowongan - KerjaYuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<nav class="navbar navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-briefcase-fill"></i> KerjaYuk</a>
        <a href="index.php" class="btn btn-gradient-outline btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</nav>

<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item active">Edit Lowongan</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="bi bi-pencil" style="color:var(--warning)"></i> Edit Lowongan
                </div>
                <div class="form-card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-section-card">
                            <div class="section-title"><i class="bi bi-info-circle"></i> Informasi Dasar</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="judul_posisi" class="form-control" id="posisi" placeholder="Judul Posisi" value="<?= htmlspecialchars($data['judul_posisi']) ?>" required>
                                        <label for="posisi"><i class="bi bi-briefcase"></i> Judul Posisi</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="nama_perusahaan" class="form-control" id="perusahaan" placeholder="Nama Perusahaan" value="<?= htmlspecialchars($data['nama_perusahaan']) ?>" required>
                                        <label for="perusahaan"><i class="bi bi-building"></i> Nama Perusahaan</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select name="tipe_pekerjaan" class="form-select" id="tipe" required>
                                            <option value="" disabled>Pilih tipe</option>
                                            <?php
                                            $tipe_options = ['Full-time', 'Part-time', 'Remote', 'Hybrid', 'Freelance'];
                                            foreach ($tipe_options as $t) {
                                                $sel = $t == $data['tipe_pekerjaan'] ? 'selected' : '';
                                                echo "<option value=\"$t\" $sel>$t</option>";
                                            }
                                            ?>
                                        </select>
                                        <label for="tipe"><i class="bi bi-clock"></i> Tipe Pekerjaan</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" name="gaji" class="form-control" id="gaji" placeholder="Gaji" value="<?= htmlspecialchars($data['gaji']) ?>" required>
                                        <label for="gaji"><i class="bi bi-cash-stack"></i> Gaji</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="date" name="deadline" class="form-control" id="deadline" placeholder="Deadline" value="<?= $data['deadline'] ?>" required>
                                        <label for="deadline"><i class="bi bi-calendar-event"></i> Deadline</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section-card">
                            <div class="section-title"><i class="bi bi-card-text"></i> Detail Lowongan</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="deskripsi" class="form-control" id="deskripsi" placeholder="Deskripsi" style="min-height:120px" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
                                        <label for="deskripsi"><i class="bi bi-list-ul"></i> Deskripsi Pekerjaan</label>
                                    </div>
                                    <div class="form-text" style="font-size:.78rem;margin-top:4px">Tiap baris otomatis jadi bullet point</div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="persyaratan" class="form-control" id="persyaratan" placeholder="Persyaratan" style="min-height:120px" required><?= htmlspecialchars($data['persyaratan']) ?></textarea>
                                        <label for="persyaratan"><i class="bi bi-list-check"></i> Persyaratan</label>
                                    </div>
                                    <div class="form-text" style="font-size:.78rem;margin-top:4px">Tiap baris otomatis jadi bullet point</div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea name="profil_perusahaan" class="form-control" id="profil" placeholder="Profil Perusahaan" style="min-height:100px" required><?= htmlspecialchars($data['profil_perusahaan']) ?></textarea>
                                        <label for="profil"><i class="bi bi-building"></i> Profil Perusahaan</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="index.php" class="btn btn-gradient-outline"><i class="bi bi-x-lg"></i> Batal</a>
                            <button type="submit" class="btn btn-gradient btn-gradient-warning"><i class="bi bi-check-lg"></i> Update Lowongan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer-custom text-center">
    <div class="container">
        <p class="mb-0">&copy; 2026 KerjaYuk Portal Lowongan Kerja</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
