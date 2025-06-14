<?php
// FILE: controllers/ProjectController.php

// Komentar ini menunjukkan bahwa inisialisasi sudah dilakukan di public/index.php
// require_once '../config/database.php'; 
// session_start(); 
// require_once '../models/Project.php'; 

// Variabel $pdo dan $_SESSION sudah tersedia di sini karena di-include oleh public/index.php

// Cek apakah request datang dari form tambah_project
if (isset($_POST['tambah_project'])) {
    if (!isset($_SESSION['user_id'])) {
        // PENTING: Arahkan ke rute login, bukan dashboard views
        header("Location: index.php?route=login"); 
        exit;
    }

    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $deadline = trim($_POST['deadline']);
    $user_id = $_SESSION['user_id'];

    if (empty($nama) || empty($deskripsi) || empty($deadline)) {
        // PENTING: Arahkan ke rute tambah_project dengan pesan error
        header("Location: index.php?route=tambah_project&error=data_tidak_lengkap");
        exit;
    }

    try {
        // Panggil fungsi dari model Project.php untuk menambah proyek
        // Menggunakan stored procedure 'TambahProjek'
        $stmt = $pdo->prepare("CALL TambahProjek(?, ?, ?, ?)");
        $stmt->execute([$nama, $deskripsi, $user_id, $deadline]);

        // PENTING: Redirect ke rute dashboard setelah tambah proyek berhasil
        header("Location: index.php?route=dashboard&success=project_added"); 
        exit;
    } catch (PDOException $e) {
        error_log("Error adding project: " . $e->getMessage()); 
        // PENTING: Redirect ke rute tambah_project dengan pesan error database
        header("Location: index.php?route=tambah_project&error=db_error&msg=" . urlencode($e->getMessage()));
        exit;
    }
}

// Tidak ada logika lain di ProjectController.php untuk skenario ini (GET request),
// karena logika GET untuk menampilkan proyek di dashboard.php sudah ada di views/dashboard.php
// dan diambil dari model/Project.php