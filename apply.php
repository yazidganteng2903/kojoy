<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$job_id = (int)$_POST['job_id'];
$nama   = $conn->real_escape_string($_POST['nama']);
$email  = $conn->real_escape_string($_POST['email']);
$no_hp  = $conn->real_escape_string($_POST['no_hp']);

$check = $conn->query("SELECT id FROM jobs WHERE id = $job_id");
if ($check->num_rows === 0) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Lowongan tidak valid!'];
    header("Location: index.php");
    exit;
}

$cv_path = null;

if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['pdf', 'doc', 'docx', 'jpg', 'png'];
    $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Format CV tidak didukung!'];
        header("Location: detail.php?id=$job_id");
        exit;
    }

    if ($_FILES['cv']['size'] > 2 * 1024 * 1024) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Ukuran CV maksimal 2MB!'];
        header("Location: detail.php?id=$job_id");
        exit;
    }

    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['cv']['name']);
    $destination = 'uploads/' . $filename;

    if (move_uploaded_file($_FILES['cv']['tmp_name'], $destination)) {
        $cv_path = $destination;
    }
}

$sql = "INSERT INTO applicants (job_id, nama, email, no_hp, cv)
        VALUES ('$job_id', '$nama', '$email', '$no_hp', " . ($cv_path ? "'$cv_path'" : "NULL") . ")";

if ($conn->query($sql)) {
    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Lamaran berhasil dikirim! Kami akan menghubungi Anda via email.'];
} else {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Gagal mengirim lamaran. Silakan coba lagi.'];
}

header("Location: detail.php?id=$job_id");
exit;
