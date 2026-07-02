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

$toast = null;
if (isset($_SESSION['toast'])) {
    $toast = $_SESSION['toast'];
    unset($_SESSION['toast']);
}

$deskripsi_lines = array_filter(explode("\n", trim($data['deskripsi'])));
$persyaratan_lines = array_filter(explode("\n", trim($data['persyaratan'])));

$deadline_ts = strtotime($data['deadline']);
$days_left = ceil(($deadline_ts - time()) / 86400);
$days_text = $days_left > 0 ? "Sisa {$days_left} hari" : ($days_left == 0 ? 'Hari ini' : 'Melewati deadline');

$avatarColors = ['#4f46e5','#0ea5e9','#f59e0b','#10b981','#ef4444','#8b5cf6','#ec4899','#14b8a6'];
$colorIdx = abs(crc32($data['nama_perusahaan'])) % count($avatarColors);
$color = $avatarColors[$colorIdx];

$badge_class = '';
switch ($data['tipe_pekerjaan']) {
    case 'Full-time': $badge_class = 'badge-fulltime'; break;
    case 'Part-time': $badge_class = 'badge-parttime'; break;
    case 'Remote': $badge_class = 'badge-remote'; break;
    case 'Hybrid': $badge_class = 'badge-hybrid'; break;
    case 'Freelance': $badge_class = 'badge-freelance'; break;
    default: $badge_class = 'badge-fulltime';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['judul_posisi']) ?> - KerjaYuk</title>
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

<div class="toast-container-fixed" id="toastContainer">
    <?php if ($toast): ?>
    <div class="toast toast-custom toast-<?= $toast['type'] ?> show" role="alert">
        <div class="toast-header">
            <i class="bi bi-<?= $toast['type'] === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill' ?> toast-icon"></i>
            <strong class="me-auto"><?= $toast['type'] === 'success' ? 'Berhasil' : 'Gagal' ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body"><?= htmlspecialchars($toast['message']) ?></div>
    </div>
    <?php endif; ?>
</div>

<div class="detail-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge-tipe <?= $badge_class ?> mb-3"><?= htmlspecialchars($data['tipe_pekerjaan']) ?></span>
                <h1><?= htmlspecialchars($data['judul_posisi']) ?></h1>
                <p class="hero-company"><i class="bi bi-building"></i> <?= htmlspecialchars($data['nama_perusahaan']) ?></p>
                <div class="hero-info">
                    <span class="hero-salary"><i class="bi bi-cash-stack"></i> <?= htmlspecialchars($data['gaji']) ?></span>
                    <span class="hero-deadline"><i class="bi bi-calendar-event"></i> Deadline: <?= date('d M Y', $deadline_ts) ?> • <?= $days_text ?></span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div style="width:80px;height:80px;border-radius:16px;background:<?= $color ?>;display:inline-flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:#fff">
                    <?php
                    $w = explode(' ', $data['nama_perusahaan']);
                    $in = '';
                    foreach ($w as $ww) $in .= strtoupper(substr($ww,0,1));
                    echo substr($in,0,2);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="detail-card" style="animation-delay:0s">
                <div class="section-header">
                    <i class="bi bi-card-text" style="background:#4f46e5"></i>
                    Deskripsi Pekerjaan
                </div>
                <ul>
                    <?php foreach ($deskripsi_lines as $line): ?>
                        <li><?= htmlspecialchars(trim($line)) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="detail-card" style="animation-delay:.1s">
                <div class="section-header">
                    <i class="bi bi-list-check" style="background:#0ea5e9"></i>
                    Persyaratan
                </div>
                <ul>
                    <?php foreach ($persyaratan_lines as $line): ?>
                        <li><?= htmlspecialchars(trim($line)) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="detail-card" style="animation-delay:.2s">
                <div class="section-header">
                    <i class="bi bi-building" style="background:#f59e0b"></i>
                    Profil Perusahaan
                </div>
                <p class="mb-0" style="line-height:1.7"><?= nl2br(htmlspecialchars($data['profil_perusahaan'])) ?></p>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="apply-sticky">
                <div class="apply-card">
                    <div class="apply-header"><i class="bi bi-send"></i> Quick Apply</div>
                    <div class="apply-body">
                        <form action="apply.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="job_id" value="<?= $data['id'] ?>">

                            <div class="form-floating mb-3">
                                <input type="text" name="nama" class="form-control" id="floatingNama" placeholder="Nama" required>
                                <label for="floatingNama"><i class="bi bi-person"></i> Nama Lengkap</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Email" required>
                                <label for="floatingEmail"><i class="bi bi-envelope"></i> Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="no_hp" class="form-control" id="floatingHp" placeholder="No HP" required>
                                <label for="floatingHp"><i class="bi bi-telephone"></i> No. HP</label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size:.85rem;font-weight:500;color:var(--text-muted)"><i class="bi bi-paperclip"></i> Upload CV (opsional)</label>
                                <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" style="font-size:.85rem">
                                <div class="form-text" style="font-size:.75rem">PDF, DOC, JPG, PNG. Maks 2MB</div>
                            </div>
                            <button type="submit" class="btn-apply"><i class="bi bi-send"></i> Kirim Lamaran</button>
                        </form>
                    </div>
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
<script>
document.querySelectorAll('.toast-custom.show').forEach(el => {
    setTimeout(() => {
        const t = bootstrap.Toast.getInstance(el);
        if (t) t.hide();
    }, 4000);
});
</script>
</body>
</html>
