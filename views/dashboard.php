<?php
// FILE: views/dashboard.php

// Komentar-komentar ini menunjukkan bahwa inisialisasi sudah dilakukan di public/index.php
// session_start(); 
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }
// require_once '../config/database.php';
// require_once '../models/Task.php'; 

// Pastikan model Project.php sudah di-require (ini dilakukan di public/index.php)
// require_once '../models/Project.php'; 

// Ambil daftar proyek milik user yang login
// Variabel $_SESSION dan $pdo DIJAMIN sudah tersedia di sini karena di-include oleh public/index.php
$projects = getProjectsByUserId($_SESSION['user_id'], $pdo);

// Menampilkan pesan sukses jika ada dari redirect ProjectController
$message = '';
if (isset($_GET['success']) && $_GET['success'] == 'project_added') {
    $message = '<p style="color: green;">Proyek baru berhasil ditambahkan!</p>';
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; margin: 2rem; }
        a.button { display: inline-block; margin: 10px 10px 20px 0; text-decoration: none; background: #28a745; color: white; padding: 10px 15px; border-radius: 4px; }
        .project-list { margin-top: 20px; }
        .project-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .project-item h3 { margin-top: 0; margin-bottom: 10px; }
        .project-item h3 a {
            text-decoration: none;
            color: #007bff; /* Warna biru untuk tautan */
            font-size: 1.2em;
        }
        .project-item p { margin-bottom: 5px; color: #555; }

        a.logout-button {
        display: inline-block;
        margin: 10px 0 20px 10px; /* Sesuaikan margin agar tidak terlalu dekat dengan tombol lain */
        text-decoration: none;
        background: #dc3545; /* Warna merah untuk logout */
        color: white;
        padding: 10px 15px;
        border-radius: 4px;
    }
    </style>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Selamat datang, User ID: <?= htmlspecialchars($_SESSION['user_id']); ?></p>

    <?php echo $message; // Tampilkan pesan sukses di sini ?>

    <a href="index.php?route=tambah_project" class="button">➕ Tambah Proyek</a>
    <a href="index.php?route=logout" class="logout-button">🚪 Logout</a>
    
    <h2>Daftar Proyek Anda</h2>

    <?php if (count($projects) === 0): ?>
        <p>Anda belum memiliki proyek. Silakan 
           <a href="index.php?route=tambah_project">tambah proyek baru</a>.
        </p>
    <?php else: ?>
        <div class="project-list">
            <?php foreach ($projects as $project): ?>
                <div class="project-item">
                    <h3>
                        <a href="index.php?route=project_tasks&project_id=<?= htmlspecialchars($project['id']); ?>">
                            <?= htmlspecialchars($project['nama_projek']); ?>
                        </a>
                    </h3>
                    <p><strong>Deskripsi:</strong> <?= htmlspecialchars($project['deskripsi']); ?></p>
                    <p><strong>Deadline:</strong> <?= htmlspecialchars($project['deadline']); ?></p>
                    </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</body>
</html>