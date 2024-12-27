<?php
// Mengatur parameter koneksi database
$servername = "localhost";  // atau '127.0.0.1' jika localhost tidak bekerja
$username = "root";         // username database Anda
$password = "";             // password database Anda (biasanya kosong di XAMPP)
$dbname = "db_hotel";          // nama database Anda

// Membuat koneksi
$koneksi = mysqli_connect($servername, $username, $password, $dbname);

// Memeriksa koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
