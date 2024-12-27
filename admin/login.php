<?php
// Mulai session
session_start();
require 'koneksi.php'; // Pastikan koneksi sudah benar

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mengambil data user berdasarkan email
    $query = "SELECT id_user, email, password FROM login_admin WHERE email = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $id_user, $db_email, $db_password);
        mysqli_stmt_fetch($stmt);

        // Verifikasi password
        if (password_verify($password, $db_password)) {
            // Password cocok, set session dan redirect
            $_SESSION['id_user'] = $id_user;
            $_SESSION['email'] = $db_email;
            $_SESSION['status'] = 'login';

            // Redirect ke halaman utama setelah login berhasil
            header("Location: index.php");
            exit();  // Pastikan eksekusi dihentikan setelah redirect
        } else {
            // Password salah
            header("Location: login.php?status=gagal&message=password_salah");
            exit();
        }
    } else {
        // Email tidak ditemukan
        header("Location: login.php?status=gagal&message=email_tidak_ditemukan");
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($koneksi);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <p class="text-center text-3xl font-bold mb-4">Login Admin</p>
        <p class="text-center mb-6">Masukkan email dan password Anda untuk masuk.</p>

        <!-- Form Login -->
        <form class="flex flex-col" action="login.php" method="post">
            <!-- Input Email -->
            <div class="flex flex-col mb-4">
                <input type="email" name="email" placeholder="Email" class="w-full py-2 px-4 border border-gray-300 rounded-md" required />
            </div>

            <!-- Input Password -->
            <div class="flex flex-col mb-6">
                <input type="password" name="password" placeholder="Password" class="w-full py-2 px-4 border border-gray-300 rounded-md" required />
            </div>

            <!-- Submit Button -->
            <button type="submit" name="login" class="w-full bg-blue-600 text-white py-2 rounded-md">Login</button>

            <!-- Pesan Error -->
            <?php
            if (isset($_GET['status']) && $_GET['status'] == 'gagal') {
                if (isset($_GET['message'])) {
                    $message = $_GET['message'];
                    if ($message == 'password_salah') {
                        echo "<p class='text-red-500 text-center mt-4'>Password salah. Coba lagi.</p>";
                    } elseif ($message == 'email_tidak_ditemukan') {
                        echo "<p class='text-red-500 text-center mt-4'>Email tidak ditemukan. Coba lagi.</p>";
                    }
                }
            }
            ?>
        </form>
    </div>

</body>
</html>
