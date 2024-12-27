<?php
session_start();
require 'koneksi.php';

// Cek jika ID user tersedia
if (!isset($_GET['id_user'])) {
    header("Location: list.php");
    exit;
}

// Ambil nilai id_user dari URL
$id_user = $_GET['id_user'];

// Pastikan id_user adalah angka (validasi input)
if (!is_numeric($id_user)) {
    echo "ID User tidak valid!";
    exit;
}

// Siapkan query untuk menghapus data user
$query = "DELETE FROM login WHERE id_user = ?";

// Gunakan prepared statement untuk menghindari SQL injection
if ($stmt = mysqli_prepare($koneksi, $query)) {
    // Bind parameter (integer)
    mysqli_stmt_bind_param($stmt, "i", $id_user);

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        // Redirect setelah berhasil menghapus data
        header("Location: list.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }

    // Tutup prepared statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($koneksi);
}

// Tutup koneksi database
mysqli_close($koneksi);
?>
