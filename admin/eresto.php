<?php
session_start();
require 'koneksi.php';

// Cek apakah id restoran ada
if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$id = $_GET['id'];

// Ambil data restoran berdasarkan ID
$query = "SELECT * FROM restaurant WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_restoran = $_POST['nama_restoran'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];
    
    // Proses upload gambar baru jika ada
    $gambar = $_FILES['gambar']['name'];
    if ($gambar != '') {
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        $gambar_path = 'uploads/' . basename($gambar);
        move_uploaded_file($gambar_tmp, $gambar_path);
    } else {
        $gambar_path = $row['gambar'];  // Gunakan gambar lama jika tidak ada gambar baru
    }

    $query = "UPDATE restaurant SET nama_restoran = '$nama_restoran', deskripsi = '$deskripsi', harga = '$harga', status = '$status', gambar = '$gambar_path' WHERE id = $id";
    
    if (mysqli_query($koneksi, $query)) {
        header("Location: lrestoran.php");  // Halaman daftar restoran
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksi);
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
        /* Gaya CSS untuk sidebar dan konten utama tetap sama */
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

        .card-header {
            padding: 16px;
            text-align: right;
        }

        .card-header p {
            font-size: 0.875rem;
            color: #4b5563;
        }

        .card-header h4 {
            font-size: 2rem;
            color: #1f2937;
        }

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
                <div class="absolute left-0 hidden mt-2 space-y-2 bg-white border border-gray-200 rounded-lg shadow-lg w-60 top-12 group-hover:block">
                    <a href="list.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Daftar Restoran</a>
                    <a href="tresto.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Tambah Restoran</a>
                </div>
            </div>
        </nav>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="breadcrumb-container">
        <div class="flex justify-between">
            <div>
                <h1 class="text-xl font-bold">Edit Restoran</h1>
            </div>
        </div>
    </div>

    <!-- Form Edit Restoran -->
    <div class="mt-8">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="nama_restoran" class="block text-sm font-medium text-gray-700">Nama Restoran</label>
                <input type="text" name="nama_restoran" id="nama_restoran" value="<?php echo $row['nama_restoran']; ?>" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg"><?php echo $row['deskripsi']; ?></textarea>
            </div>

            <div class="mb-4">
                <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="text" name="harga" id="harga" value="<?php echo $row['harga']; ?>" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="open" <?php echo ($row['status'] == 'open') ? 'selected' : ''; ?>>Open</option>
                    <option value="closed" <?php echo ($row['status'] == 'closed') ? 'selected' : ''; ?>>Closed</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="gambar" class="block text-sm font-medium text-gray-700">Upload Gambar</label>
                <input type="file" name="gambar" id="gambar" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg">
                <img src="<?php echo $row['gambar']; ?>" alt="Gambar Restoran" class="mt-2 w-32 h-32 object-cover rounded-lg">
            </div>

            <div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
