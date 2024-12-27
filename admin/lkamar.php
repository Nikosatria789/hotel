<?php
session_start();
require 'koneksi.php';

// Query untuk mengambil data kamar
$query = "SELECT * FROM kamar";
$result = mysqli_query($koneksi, $query);

// Periksa apakah query berhasil
if (!$result) {
    die('Query gagal: ' . mysqli_error($koneksi));
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
        /* Custom CSS for sidebar */
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
</head>
<body class="font-sans bg-gray-100">

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-content flex flex-col h-full p-4">
        <div class="flex items-center mt-10">
            <a href="profil.php" class="flex items-center space-x-2">
                <div id="userProfileImage" class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white text-lg font-bold rounded-full">
                    <?php echo $initials; ?>
                </div>
            </a>
            <div class="ml-3">
                <h3 class="font-medium text-gray-700">User</h3>
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
                    <svg class="ml-auto h-5 w-5 transform transition-transform" id="dropdownIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                <!-- Dropdown Menu -->
                <div id="dropdownMenu" class="absolute left-0 mt-2 w-full bg-white text-black rounded-lg shadow-lg opacity-0 transition-opacity z-50">
                    <a href="lkamar.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Kamar</a>
                    <a href="lresto.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Keamanan</a>
                    <a href="area.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Area</a>
                </div>
            </div>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="breadcrumb-container mb-6">
        <nav class="flex items-center text-sm text-gray-600">
            <a href="index.php" class="hover:text-rose-600">Kamar</a>
            <svg class="mx-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l7-7 7 7" />
            </svg>
            <span class="text-sm font-medium text-gray-600">Daftar Kamar</span>
        </nav>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Daftar Kamar</h2>
    <a href="tkamar.php" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Tambah Kamar</a><br><br>

    <!-- Tabel Daftar Kamar -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 border-b">ID</th>
                    <th class="py-3 px-4 border-b">Tipe Kamar</th>
                    <th class="py-3 px-4 border-b">Deskripsi</th>
                    <th class="py-3 px-4 border-b">Harga</th>
                    <th class="py-3 px-4 border-b">Status</th>
                    <th class="py-3 px-4 border-b">Gambar</th>
                    <th class="py-3 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Path gambar relatif dari root website
            $gambar_path = '/hotel/uploads/' . $row['gambar'];
            
            // Debugging: Cek path gambar
            // echo $gambar_path; // Uncomment untuk debug

            // Jika gambar tidak ditemukan, gunakan gambar default
            $gambar_full_path = $_SERVER['DOCUMENT_ROOT'] . $gambar_path;
            if (!file_exists($gambar_full_path)) {
                $gambar_path = '/uploads/default.jpg';  // fallback gambar
            }

            echo "<tr>";
            echo "<td class='py-3 px-4 border-b'>{$row['id']}</td>";
            echo "<td class='py-3 px-4 border-b'>{$row['tipe_kamar']}</td>";
            echo "<td class='py-3 px-4 border-b'>{$row['deskripsi']}</td>";
            echo "<td class='py-3 px-4 border-b'>" . number_format($row['harga'], 0, ',', '.') . "</td>";
            echo "<td class='py-3 px-4 border-b'>{$row['status']}</td>";
            $gambar_path = '/hotel/uploads/' . $row['gambar'];
            echo "<td class='py-3 px-4 border-b'><img src='{$gambar_path}' alt='Gambar Kamar' class='w-20 h-20 object-cover'></td>";
            

            echo "<td class='py-3 px-4 border-b'>
                    <a href='ekamar.php?id={$row['id']}' class='text-blue-600'>Edit</a> | 
                    <a href='delet.php?id={$row['id']}' class='text-red-600' onclick='return confirm(\"Apakah Anda yakin ingin menghapus?\")'>Hapus</a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7' class='py-3 px-4 text-center'>Tidak ada kamar yang tersedia.</td></tr>";
    }
    ?>
</tbody>

        </table>
    </div>
</div>

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
