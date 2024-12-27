<?php
session_start();
require 'koneksi.php';

// Cek jika ID kamar tersedia
if (!isset($_GET['id'])) {
    header("Location: lkamar.php");
    exit;
}

$id = $_GET['id'];

// Hapus gambar dari server (opsional)
$query = "SELECT gambar FROM kamar WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);
if ($row) {
    unlink('uploads/' . $row['gambar']);
}

// Hapus data kamar dari database
$query = "DELETE FROM kamar WHERE id = $id";
if (mysqli_query($koneksi, $query)) {
    header("Location: lkamar.php");
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
