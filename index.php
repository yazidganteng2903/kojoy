<?php
session_start();
include 'config/koneksi.php';

$toast = null;
if (isset($_SESSION['toast'])) {
    $toast = $_SESSION['toast'];
    unset($_SESSION['toast']);
}

$result = $conn->query("SELECT * FROM jobs ORDER BY deadline ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Lowongan Kerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<nav class="navbar navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-briefcase-fill"></i> KerjaYuk</a>
        <a href="tambah.php" class="btn btn-gradient btn-sm"><i class="bi bi-plus-lg"></i> Tambah Lowongan</a>
    </div>
</nav>

<div class="hero-section text-center">
    <div class="container">
        <h1>Temukan Pekerjaan Impianmu</h1>
        <p class="mb-3">Jelajahi ribuan lowongan dari perusahaan terbaik</p>
        <div class="search-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input type="text" class="form-control" id="searchInput" placeholder="Cari posisi atau perusahaan..." autocomplete="off">
        </div>
    </div>
</div>

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

<div class="container pb-5">
    <div class="row g-4" id="jobGrid">
        <?php if ($result->num_rows > 0): ?>
            <?php $avatarColors = ['#4f46e5','#0ea5e9','#f59e0b','#10b981','#ef4444','#8b5cf6','#ec4899','#14b8a6']; ?>
            <?php $idx = 0; while ($row = $result->fetch_assoc()): ?>
            <?php
                $idx++;
                $color = $avatarColors[$idx % count($avatarColors)];
                $initials = '';
                $words = explode(' ', $row['nama_perusahaan']);
                foreach ($words as $w) $initials .= strtoupper(substr($w, 0, 1));
                $initials = substr($initials, 0, 2);

                $deadline_ts = strtotime($row['deadline']);
                $days_left = ceil(($deadline_ts - time()) / 86400);
                $days_text = $days_left > 0 ? "Sisa {$days_left} hari" : ($days_left == 0 ? 'Hari ini' : 'Melewati deadline');

                $badge_class = '';
                switch ($row['tipe_pekerjaan']) {
                    case 'Full-time': $badge_class = 'badge-fulltime'; break;
                    case 'Part-time': $badge_class = 'badge-parttime'; break;
                    case 'Remote': $badge_class = 'badge-remote'; break;
                    case 'Hybrid': $badge_class = 'badge-hybrid'; break;
                    case 'Freelance': $badge_class = 'badge-freelance'; break;
                    default: $badge_class = 'badge-fulltime';
                }
            ?>
            <div class="col-md-6 col-lg-4 job-card-wrapper">
                <div class="job-card">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="company-avatar" style="background:<?= $color ?>">
                            <?= $initials ?>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="job-title text-truncate"><?= htmlspecialchars($row['judul_posisi']) ?></div>
                            <div class="company-name text-truncate"><?= htmlspecialchars($row['nama_perusahaan']) ?></div>
                        </div>
                    </div>

                    <div>
                        <span class="badge-tipe <?= $badge_class ?>"><?= htmlspecialchars($row['tipe_pekerjaan']) ?></span>
                    </div>

                    <div class="job-meta">
                        <span class="salary"><i class="bi bi-cash-stack"></i> <?= htmlspecialchars($row['gaji']) ?></span>
                        <span class="<?= $days_left <= 7 && $days_left > 0 ? 'deadline-urgent' : 'deadline' ?>">
                            <i class="bi bi-clock"></i> <?= date('d M', $deadline_ts) ?> • <?= $days_text ?>
                        </span>
                    </div>

                    <div class="card-actions">
                        <a href="detail.php?id=<?= $row['id'] ?>" class="btn-detail text-center"><i class="bi bi-eye"></i> Detail</a>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn-icon btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn-icon btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus lowongan ini?')"><i class="bi bi-trash"></i></a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-briefcase"></i>
                    <h5>Belum ada lowongan tersedia</h5>
                    <p class="text-muted mb-3">Mulai dengan menambahkan lowongan pertama</p>
                    <a href="tambah.php" class="btn btn-gradient"><i class="bi bi-plus-lg"></i> Tambah Lowongan</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="footer-custom text-center">
    <div class="container">
        <p class="mb-0">&copy; 2026 KerjaYuk Portal Lowongan Kerja</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ─── Search Filter ───
document.getElementById('searchInput')?.addEventListener('keyup', function() {
    const keyword = this.value.toLowerCase().trim();
    document.querySelectorAll('.job-card-wrapper').forEach(wrapper => {
        const text = wrapper.textContent.toLowerCase();
        wrapper.style.display = text.includes(keyword) ? '' : 'none';
    });
});

// ─── Toast Auto Dismiss ───
document.querySelectorAll('.toast-custom.show').forEach(el => {
    setTimeout(() => {
        const toast = bootstrap.Toast.getInstance(el);
        if (toast) toast.hide();
    }, 4000);
});
</script>
</body>
</html>
