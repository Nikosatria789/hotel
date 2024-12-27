<?php
session_start();
require 'koneksi.php';

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $tipe_kamar = mysqli_real_escape_string($koneksi, $_POST['tipe_kamar']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    // Proses upload gambar
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    
    // Path ke folder uploads di luar folder admin
    // Gunakan $_SERVER['DOCUMENT_ROOT'] untuk memastikan path yang benar
    $gambar_path = $_SERVER['DOCUMENT_ROOT'] . '/hotel/uploads/' . basename($gambar);

    // Debug: Periksa path
    echo "Path gambar: " . $gambar_path; // Debug path yang digunakan

    // Validasi tipe dan ukuran file gambar
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    // Cek apakah file yang diupload adalah gambar dan ukuran tidak melebihi batas
    if (!in_array($_FILES['gambar']['type'], $allowed_types)) {
        $uploadError = "Hanya gambar dengan format JPG, JPEG, atau PNG yang diperbolehkan.";
    } elseif ($_FILES['gambar']['size'] > $max_size) {
        $uploadError = "Ukuran file terlalu besar. Maksimum 5MB.";
    } else {
        // Cek apakah folder uploads ada dan bisa diakses
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/hotel/uploads/')) {
            echo "Folder uploads tidak ditemukan!"; // Akan ditampilkan jika folder tidak ada
            exit;
        }

        // Proses upload file
        if (move_uploaded_file($gambar_tmp, $gambar_path)) {
            // Query untuk memasukkan data ke database
            $query = "INSERT INTO kamar (tipe_kamar, deskripsi, harga, status, gambar) 
                      VALUES ('$tipe_kamar', '$deskripsi', '$harga', '$status', '$gambar')";
            
            if (mysqli_query($koneksi, $query)) {
                header("Location: lkamar.php");
                exit;
            } else {
                echo "Error: " . mysqli_error($koneksi);
            }
        } else {
            $uploadError = "Gagal mengupload gambar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <title>Dashboard</title>
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 16rem; /* Ukuran lebar sidebar */
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar-content {
            overflow-y: auto;
            height: 100%;
            padding: 20px;
        }

        .main-content {
            margin-left: 16rem; /* Memberi jarak dari sidebar */
            padding: 2rem;
            background-color: #f9fafb;
        }

        /* Menambahkan border dan padding untuk setiap card */
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: white;
            width: 100%;
            margin: 10px 0;
        }

        .card .icon {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f4f6;
            margin-top: -30px;
        }

        /* Mengatur header pada kartu */
        .card-header {
            padding: 16px;
            text-align: right;
        }

        /* Styling teks dan angka */
        .card-header p {
            font-size: 0.875rem;
            color: #4b5563;
        }

        .card-header h4 {
            font-size: 2rem;
            color: #1f2937;
        }

        /* Styling bagian bawah kartu */
        .card-footer {
            padding: 16px;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .breadcrumb-container {
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 16px;
        }

        /* Membuat konten halaman responsive */
        @media (max-width: 1024px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar {
                width: 100%;
                position: relative;
                z-index: 1;
            }

            .card {
                width: 100%;
            }
        }
    </style>
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
                <h3 class="font-medium">User</h3>
                <p class="text-xs text-gray-500">Admin Boss</p>
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
            <a href="settings.php" class="flex items-center py-2 px-4 text-sm font-medium text-gray-600 hover:border-l-4 hover:border-l-rose-600 hover:text-rose-600">
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
                    <svg class="ml-auto h-5 w-5 transform transition-transform" id="dropdownIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                <!-- Dropdown Menu -->
                <div id="dropdownMenu" class="absolute left-0 mt-2 w-full bg-white text-black rounded-lg shadow-lg opacity-0 transition-opacity z-50">
                    <a href="lkamar.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Kamar</a>
                    <a href="lresto.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">restaurant</a>
                </div>
            </div>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="breadcrumb-container">
        <nav class="flex items-center">
            <a href="index.php" class="text-sm font-medium text-gray-600">Dashboard</a>
            <svg class="mx-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l7-7 7 7" />
            </svg>
            <span class="text-sm font-medium text-gray-600">Form tambah kamar</span>
        </nav>
    </div>

    <div class="card p-6">
    <h2>Tambah Kamar</h2>
    <form action="" method="POST" enctype="multipart/form-data">
    <div class="mb-4">
        <label for="tipe_kamar" class="block">Tipe Kamar</label>
        <input type="text" name="tipe_kamar" id="tipe_kamar" class="w-full p-2 border border-gray-300 rounded" required>
    </div>

    <div class="mb-4">
        <label for="deskripsi" class="block">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" class="w-full p-2 border border-gray-300 rounded" required></textarea>
    </div>

    <div class="mb-4">
        <label for="harga" class="block">Harga</label>
        <input type="number" name="harga" id="harga" class="w-full p-2 border border-gray-300 rounded" required>
    </div>

    <div class="mb-4">
        <label for="status" class="block">Status</label>
        <select name="status" id="status" class="w-full p-2 border border-gray-300 rounded" required>
            <option value="tersedia">Tersedia</option>
            <option value="tidak tersedia">Tidak Tersedia</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="gambar" class="block">Gambar</label>
        <input type="file" name="gambar" id="gambar" class="w-full p-2 border border-gray-300 rounded" required>
        <!-- Tampilkan pesan error jika gambar gagal diupload -->
        <?php if (isset($uploadError)) { echo '<p class="text-red-500">'.$uploadError.'</p>'; } ?>
    </div>

    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Tambah Kamar</button>
</form>

<script>
    // JavaScript for toggling dropdown
    document.getElementById('settingsButton').addEventListener('click', function() {
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownIcon = document.getElementById('dropdownIcon');
        dropdownMenu.classList.toggle('opacity-100');
        dropdownMenu.classList.toggle('opacity-0');
        dropdownIcon.classList.toggle('transform rotate-180');
    });
</script>



</body>
</html>
