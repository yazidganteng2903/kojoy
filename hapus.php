<?php
include 'config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$check = $conn->query("SELECT * FROM jobs WHERE id = $id");
if ($check->num_rows === 0) {
    header("Location: index.php?status=error&message=Lowongan tidak ditemukan!");
    exit;
}

$conn->query("DELETE FROM jobs WHERE id = $id");

header("Location: index.php?status=success&message=Lowongan berhasil dihapus!");
exit;
