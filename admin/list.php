<?php
// Menyertakan file koneksi
require 'koneksi.php';
session_start(); // Start the session at the top of the file


// Cek koneksi database
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Cek apakah pengguna yang login adalah admin atau user biasa
$id_user = 1; // Ganti dengan nilai session atau mekanisme login lainnya

// Menyusun query berdasarkan jenis user (admin atau user biasa)
if ($id_user == 1) {
    // Ambil data admin
    $query = "SELECT id_user, email, password FROM login_admin WHERE id_user = ?";
} else {
    // Ambil data user biasa
    $query = "SELECT id_user, username, email FROM login WHERE id_user = ?";
}

// Eksekusi query untuk mengambil data pengguna dengan prepared statement
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Query preparation gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id_user); // "i" untuk integer
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Mengecek apakah query berhasil
if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Mengecek apakah ada data yang ditemukan
if (mysqli_num_rows($result) > 0) {
    // Ambil data pengguna
    $user = mysqli_fetch_assoc($result);
} else {
    // Jika tidak ada data ditemukan, tampilkan pesan error
    die("Data pengguna tidak ditemukan.");
}

// Query untuk mengambil data user list (termasuk admin dan user biasa)
$user_list_query = "SELECT id_user, username, email, create_at FROM login";
$user_list_result = mysqli_query($koneksi, $user_list_query);

// Mengecek apakah query berhasil
if (!$user_list_result) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Query untuk mengambil data bookings sesuai dengan user yang login
$bookings_query = "SELECT * FROM bookings WHERE email = 'user@gmail.com'= ?";
$stmt_bookings = mysqli_prepare($koneksi, $bookings_query);

if (!$stmt_bookings) {
    die("Query preparation gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt_bookings, "s", $user['email']); // "s" untuk string
mysqli_stmt_execute($stmt_bookings);
$bookings_result = mysqli_stmt_get_result($stmt_bookings);

// Mengecek apakah query berhasil
if (!$bookings_result) {
    die("Query gagal: " . mysqli_error($koneksi));
} 



$user_email = $_SESSION['email']; // Ambil email dari sesi
$initials = strtoupper(substr($user_email, 0, 1)); 

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <title>list</title>
    <style>
        /* Menetapkan posisi fixed untuk sidebar */
/* Sidebar */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 20rem; /* Memperbesar lebar sidebar */
    background-color: white;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.sidebar-content {
    overflow-y: auto;
    height: 100%;
    padding: 20px;
}

/* Konten Utama */
.main-content {
    margin-left: 16rem; /* Tetap menggunakan lebar sidebar yang sudah ada */
    padding: 3rem; /* Menambah padding agar konten lebih lebar dan memiliki ruang lebih */
    background-color: #f9fafb;
}

/* Card (untuk kartu dalam konten utama) */
.card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Shadow lebih besar agar terlihat lebih 'terangkat' */
    overflow: hidden;
    background-color: white;
    width: 100%;
    margin: 15px 0; /* Menambah jarak antar kartu */
    padding: 2rem; /* Menambah padding di dalam kartu untuk lebih luas */
}

/* Breadcrumb */
.breadcrumb-container {
    background-color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Shadow lebih ringan */
    padding: 20px; /* Padding lebih besar */
}

/* Membuat konten halaman responsive */
@media (max-width: 1024px) {
    .main-content {
        margin-left: 0; /* Menghapus margin kiri pada layar kecil */
        padding: 2rem; /* Padding lebih kecil pada layar kecil */
    }

    .card {
        margin: 20px 0; /* Jarak antar card lebih besar di layar kecil */
    }

    .sidebar {
        width: 100%; /* Sidebar mengambil lebar penuh pada layar kecil */
        position: relative; /* Sidebar menjadi relatif pada layar kecil */
    }
}

    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-content flex flex-col h-full">
        <div class="flex mt-10 items-center px-4">
            <a href="profil.php" class="flex items-center space-x-2">
                <div id="userProfileImage" class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white text-lg font-bold rounded-full">
                    <?php echo $initials; ?>
                </div>
            </a>
            <div class="ml-3">
                <h3 class="font-medium">user</h3>
                <p class="text-xs text-gray-500">admin boss</p>
            </div>
        </div>

        <!-- Menu Sidebar -->
        <nav class="mt-10">
            <a href="index.php" class="flex items-center py-2 px-4 text-sm font-medium text-rose-600 border-l-4 border-l-rose-600">
                <svg class="mr-4 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            <a href="list.php" class="flex items-center py-2 px-4 text-sm font-medium text-gray-600 hover:border-l-4 hover:border-l-rose-600 hover:text-rose-600">
                <svg class="mr-4 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c4.418 0 8 3.582 8 8H4c0-4.418 3.582-8 8-8z" />
                </svg>
                User List
            </a>
            <a href="form.php" class="flex items-center py-2 px-4 text-sm font-medium text-gray-600 hover:border-l-4 hover:border-l-rose-600 hover:text-rose-600">
                <svg class="mr-4 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c4.418 0 8 3.582 8 8H4c0-4.418 3.582-8 8-8z" />
                </svg>
                Form Pemesanan
            </a>
            <a href="komentar.php" class="flex items-center py-2 px-4 text-sm font-medium text-gray-600 hover:border-l-4 hover:border-l-rose-600 hover:text-rose-600">
                <svg class="mr-4 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c4.418 0 8 3.582 8 8H4c0-4.418 3.582-8 8-8z" />
                </svg>
                komentar
            </a>
            <div class="relative group">
                <a href="#" id="settingsButton" class="flex items-center py-2 px-4 text-sm font-medium text-gray-600 hover:border-l-4 hover:border-l-rose-600 hover:text-rose-600">
                    <svg class="mr-4 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c4.418 0 8 3.582 8 8H4c0-4.418 3.582-8 8-8z" />
                    </svg>
                    Pengaturan Tempat
                    <!-- Dropdown Icon -->
                    <svg class="h-4 w-4 ml-auto transform group-hover:rotate-180 transition duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                <div id="settingsDropdown" class="absolute left-0 hidden bg-white shadow-md w-48 mt-2 py-2 rounded-md border border-gray-200 z-10">
                    <a href="lkamar.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">kamar</a>
                    <a href="lresto.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">restaurant</a>
                </div>
            </div>
        </nav>
        <div class="mt-auto">
            <a href="logout.php" class="flex items-center py-2 px-4 text-sm font-medium text-gray-600 hover:border-l-4 hover:border-l-rose-600 hover:text-rose-600">
                <svg class="mr-4 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9l3 3-3 3M7 9l-3 3 3 3M15 5v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h8a2 2 0 012 2z" />
                </svg>
                Log Out
            </a>
        </div>
    </div>
</div>

<!-- Konten Utama -->
<div class="main-content">
    <div class="breadcrumb-container mb-6">
        <h1 class="text-xl font-semibold">List</h1>
        <nav class="mt-2">
            <span class="text-sm text-gray-600">admin</span> / <span class="text-sm text-gray-600">List</span>
        </nav>
    </div>

    <div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-gray-700 mb-8">User List</h2>

        <!-- User List Table -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">ID User</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">Username</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-2 text-sm font-semibold text-gray-600">Created At</th>
                    </tr>
                </thead>
                <tbody>
                <?php
        if (mysqli_num_rows($user_list_result) > 0) {
            while ($user_row = mysqli_fetch_assoc($user_list_result)) {
                echo "<tr>";
                echo "<td class='border px-4 py-2'>" . $user_row['id_user'] . "</td>";
                echo "<td class='border px-4 py-2'>" . $user_row['username'] . "</td>";
                echo "<td class='border px-4 py-2'>" . $user_row['email'] . "</td>";
                echo "<td class='border px-4 py-2'>" . $user_row['create_at'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center py-4'>Tidak ada data user.</td></tr>";
        }
        ?>
                </tbody>
            </table>
        </div>

        
<h3 class="text-xl font-semibold text-gray-700 mb-4">List Booking Kamar</h3>
<div class="overflow-x-auto bg-white shadow-md rounded-lg">
    <table class="min-w-full table-auto border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">ID Booking</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Email</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Tipe Kamar</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Check-in</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Check-out</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Nama Pemesan</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Status</th>
                <th class="px-4 py-2 text-sm font-semibold text-gray-600">Konfirmasi</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if (mysqli_num_rows($bookings_result) > 0) {
                while ($booking_row = mysqli_fetch_assoc($bookings_result)) {
                    echo "<tr>";
                    echo "<td class='border px-4 py-2'>" . $booking_row['id_booking'] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $booking_row['email'] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $booking_row['tipe_kamar'] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $booking_row['check_in'] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $booking_row['check_out'] . "</td>";
                    echo "<td class='border px-4 py-2'>" . $booking_row['nama_pemesan'] . "</td>";
                    
                    // Menampilkan status pemesanan
                    echo "<td class='border px-4 py-2'>";
                    if ($booking_row['status'] == 'pending') {
                        echo "<span class='text-yellow-500'>Pending</span>";
                    } elseif ($booking_row['status'] == 'confirmed') {
                        echo "<span class='text-green-500'>Confirmed</span>";
                    } else {
                        echo "<span class='text-red-500'>Rejected</span>";
                    }
                    echo "</td>";
                    
                    // Tombol konfirmasi dan tolak
                    echo "<td class='border px-4 py-2'>";
                    if ($booking_row['status'] == 'pending') {
                        echo "<form method='POST' action='update_status.php' class='inline'>
                            <input type='hidden' name='id_booking' value='" . $booking_row['id_booking'] . "' />
                            <button type='submit' name='status' value='confirmed' class='px-4 py-2 bg-green-500 text-white rounded-lg'>Konfirmasi</button>
                        </form>";
                        
                        echo "<form method='POST' action='update_status.php' class='inline ml-2'>
                            <input type='hidden' name='id_booking' value='" . $booking_row['id_booking'] . "' />
                            <button type='submit' name='status' value='rejected' class='px-4 py-2 bg-red-500 text-white rounded-lg'>Tolak</button>
                        </form>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center py-4'>No bookings found.</td></tr>";
            }
        ?>
        </tbody>
    </table>
</div>

    </div>
</div>

<script>
// Script untuk menampilkan dan menyembunyikan dropdown pengaturan
const settingsButton = document.getElementById('settingsButton');
const settingsDropdown = document.getElementById('settingsDropdown');

settingsButton.addEventListener('click', function(event) {
    event.preventDefault();
    settingsDropdown.classList.toggle('hidden');
});
</script>

</body>
</html>
