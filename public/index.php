<?php
/**
 * File: public/index.php
 * Deskripsi: Ini adalah front controller (router) utama aplikasi WORKDIF.
 * Semua permintaan web akan melewati file ini.
 * Bertanggung jawab untuk inisialisasi sesi, koneksi database,
 * otentikasi dasar, dan routing ke tampilan/controller yang sesuai.
 */

// 1. Inisialisasi Sesi
session_start();

// 2. Sertakan Konfigurasi Database
require_once __DIR__ . '/../config/database.php';

// 3. Sertakan Model-model yang Umum Digunakan
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Project.php';
require_once __DIR__ . '/../models/Task.php';

// 5. Penanganan Routing Permintaan
// Ambil rute dari parameter GET 'route'. Jika tidak ada, default ke 'dashboard'.
$route = $_GET['route'] ?? 'dashboard';

// --- START PERUBAHAN PENTING DI SINI ---

// Rute yang TIDAK memerlukan login (whitelist)
$public_routes = ['login', 'register', 'logout']; // Tambahkan 'register' jika Anda punya halaman registrasi

// 4. Periksa Status Otentikasi Pengguna
// Lakukan redirect HANYA jika pengguna belum login DAN rute yang diminta BUKAN rute publik
if (!isset($_SESSION['user_id']) && !in_array($route, $public_routes)) {
    header("Location: index.php?route=login");
    exit;
}

// --- END PERUBAHAN PENTING ---


// Menggunakan struktur switch-case sederhana untuk routing
switch ($route) { // Menggunakan $route
    case 'dashboard':
        include __DIR__ . '/../views/dashboard.php';
        break;

    case 'project_tasks':
        include __DIR__ . '/../views/project_tasks.php';
        break;

    case 'tambah_project':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controllers/projectControler.php';
        } else {
            include __DIR__ . '/../views/tambah_project.php';
        }
        break;

    case 'tambah_task':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controllers/taskControler.php';
        } else {
            include __DIR__ . '/../views/tambah_task.php';
        }
        break;

    case 'login':
        // Jika sudah login tapi akses halaman login, bisa di-redirect ke dashboard
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?route=dashboard");
            exit;
        }

        // --- BARIS DEBUGGING DI SINI SUDAH DIHAPUS ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../controllers/AuthController.php';
        } else {
            include __DIR__ . '/../views/login.php';
        }
        break; // break untuk case 'login'

    case 'logout':
        session_destroy();
        header("Location: index.php?route=login");
        exit;
    case 'hapus_project':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id'])) {
            require_once __DIR__ . '/../controllers/projectControler.php';

            deleteProject($_POST['project_id'], $_SESSION['user_id'], $pdo);
        }
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "<p>Maaf, halaman yang Anda cari tidak ditemukan.</p>";
        break;
}
?>