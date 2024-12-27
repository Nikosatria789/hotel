<?php
// Koneksi ke database
include('koneksi.php');

// Query untuk menampilkan komentar
$sql = "SELECT * FROM komentar ORDER BY id DESC";
$result = mysqli_query($koneksi, $sql);

// Tutup koneksi database
mysqli_close($koneksi);
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
        /* Menetapkan posisi fixed untuk sidebar */
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
                form pemesanan
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
                    <a href="lresto.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-100">Restoran</a>
                </div>
            </div>
        </nav>

        <!-- Log Out at the bottom -->
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
<div class="main-content">
    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <nav class="flex items-center text-sm text-gray-600">
            <a href="index.php" class="hover:text-rose-600">Admin</a>
            <span class="mx-2">/</span>
            <a href="dashboard.php" class="hover:text-rose-600">komentar</a>
        </nav>

    </div>

    <p class="mt-5 text-lg font-bold text-gray-800">kolom komentar</p>
    <table class="min-w-full table-auto">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-2 px-4">Nama Pengguna</th>
                        <th class="py-2 px-4">Email Pengguna</th>
                        <th class="py-2 px-4">Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='py-2 px-4'>{$row['nama']}</td>";
                            echo "<td class='py-2 px-4'>{$row['email']}</td>";
                            echo "<td class='py-2 px-4'>{$row['komentar']}</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='py-2 px-4 text-center'>Tidak ada komentar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
</div>

</div>
    


<script>
    document.addEventListener('DOMContentLoaded', function() {
    const settingsButton = document.getElementById('settingsButton');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownIcon = document.getElementById('dropdownIcon');

    // Menambahkan event listener untuk klik pada tombol Settings
    settingsButton.addEventListener('click', function(e) {
        e.preventDefault();  // Mencegah aksi default (misalnya navigasi jika ada)

        // Toggle visibilitas dropdown menu
        dropdownMenu.classList.toggle('opacity-100');  // Tampilkan atau sembunyikan menu

        // Ubah rotasi ikon dropdown
        dropdownIcon.classList.toggle('rotate-180');
    });

    // Tutup dropdown jika pengguna mengklik di luar dropdown
    document.addEventListener('click', function(e) {
        if (!settingsButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('opacity-100');
            dropdownIcon.classList.remove('rotate-180');
        }
    });
});

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>
</html>
