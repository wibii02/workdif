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

// 4. Periksa Status Otentikasi Pengguna
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?route=login"); 
    exit;
}

// --- START PERUBAHAN UTAMA DI SINI ---

// 5. Penanganan Routing Permintaan
// Ambil rute dari parameter GET 'route'. Jika tidak ada, default ke 'dashboard'.
$route = $_GET['route'] ?? 'dashboard'; 

// Menggunakan struktur switch-case sederhana untuk routing
switch ($route) { // Menggunakan $route alih-alih $request_uri
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
        include __DIR__ . '/../views/login.php';
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php?route=login"); 
        exit;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "<p>Maaf, halaman yang Anda cari tidak ditemukan.</p>";
        break;
}
// --- END PERUBAHAN UTAMA DI SINI ---
?>