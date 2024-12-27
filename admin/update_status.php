<?php
// Pastikan sudah terhubung dengan database
include('koneksi.php'); // Sesuaikan dengan file koneksi Anda

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_booking = $_POST['id_booking'];
    $status = $_POST['status'];

    // Update status booking
    $update_query = "UPDATE bookings SET status = ? WHERE id_booking = ?";
    $stmt = $koneksi->prepare($update_query);
    $stmt->bind_param("si", $status, $id_booking);

    if ($stmt->execute()) {
        // Ambil data email pemesan
        $query = "SELECT email FROM bookings WHERE id_booking = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
        $email = $booking['email'];

        // Kirimkan email notifikasi
        if ($status == 'confirmed') {
            $subject = "Booking Confirmed";
            $message = "Your booking has been confirmed. Thank you for choosing us!";
        } else {
            $subject = "Booking Rejected";
            $message = "Your booking has been rejected. Please contact support for further details.";
        }

        // Fungsi untuk mengirim email
        // Sesuaikan 'From' email dengan email yang valid di server Anda
        $headers = "From: user@gmail.com";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // Kirim email menggunakan mail()
        mail($email, $subject, $message, $headers);

        // Set notifikasi sukses atau gagal
        $_SESSION['notification'] = "Status updated successfully!";
        header('Location: list.php'); // Kembali ke halaman utama booking
    } else {
        $_SESSION['notification'] = "Failed to update status. Please try again.";
        header('Location: list.php'); // Kembali ke halaman utama booking
    }

    $_SESSION['notification'] = "Status updated successfully!";
header('Location: list.php'); // Kembali ke halaman utama booking

// Debugging: Cek apakah session berhasil diset
echo "<pre>";
print_r($_SESSION);  // Menampilkan semua data session
echo "</pre>";
exit;

    $stmt->close();
    $koneksi->close();
}
?>
