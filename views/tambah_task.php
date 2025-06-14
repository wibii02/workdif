<?php
// FILE: views/tambah_task.php

// Komentar ini menunjukkan bahwa inisialisasi sudah dilakukan di public/index.php
// session_start(); 
// if (!isset($_SESSION['user_id'])) { 
//     header("Location: login.php"); 
//     exit; 
// }

// Ambil project_id dari URL (jika ada)
$project_id = $_GET['project_id'] ?? null;

// Jika project_id tidak ada, segera redirect ke dashboard
if (!$project_id) {
    header("Location: index.php?route=dashboard&error=no_project_id_for_task");
    exit;
}

// Anda bisa menambahkan logika untuk mengambil nama proyek berdasarkan $project_id
// untuk ditampilkan di halaman ini, agar pengguna tahu proyek mana yang sedang ditambahkan tugasnya.
require_once '../models/Project.php'; // Pastikan Project.php di-require
$projectName = '';
// Variabel $pdo sudah tersedia di sini karena di-include oleh public/index.php
$project = getProjectById($project_id, $pdo);
if ($project) {
    $projectName = htmlspecialchars($project['nama_projek']);
} else {
    // Jika project_id ada tapi proyek tidak ditemukan atau bukan milik user
    header("Location: index.php?route=dashboard&error=invalid_project_id");
    exit;
}


// Menampilkan pesan error atau success dari redirect
$message = '';
if (isset($_GET['success']) && $_GET['success'] == 'task_added') {
    $message = '<p style="color: green;">Task berhasil ditambahkan!</p>';
} elseif (isset($_GET['error'])) {
    $error_msg = $_GET['error'];
    if ($error_msg == 'data_tidak_lengkap') {
        $message = '<p style="color: red;">Mohon lengkapi semua bidang yang wajib diisi.</p>';
    } elseif ($error_msg == 'db_error') {
        $message = '<p style="color: red;">Terjadi kesalahan database: ' . htmlspecialchars($_GET['msg'] ?? 'Unknown error') . '</p>';
    } elseif ($error_msg == 'no_project_id_for_task') { // Menambahkan pesan jika tidak ada project_id awal
        $message = '<p style="color: red;">Kesalahan: ID proyek tidak ditemukan untuk menambahkan tugas.</p>';
    } elseif ($error_msg == 'invalid_project_id') { // Menambahkan pesan jika project_id tidak valid
        $message = '<p style="color: red;">Kesalahan: Proyek tidak valid atau tidak dapat diakses.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Task</title>
    <style>
        body { font-family: Arial; margin: 2rem; }
        form { margin-top: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; max-width: 500px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], input[type="date"], textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        button:hover { background-color: #0056b3; }
        a { display: inline-block; margin-top: 10px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <h1>Tambah Task</h1>
    <?php if ($projectName): ?>
        <p>Untuk Proyek: <strong><?= $projectName; ?></strong></p>
    <?php endif; ?>

    <?php echo $message; ?>

    <form method="POST" action="index.php?route=tambah_task">
        <input type="hidden" name="project_id" value="<?= htmlspecialchars($project_id); ?>"> 
        
        <label for="judul">Judul Task:</label>
        <input type="text" id="judul" name="judul" placeholder="Judul Task" required>
        
        <label for="deskripsi">Deskripsi Task:</label>
        <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsi Task"></textarea>
        
        <label for="assigned_to">User ID Penanggung Jawab:</label>
        <input type="number" id="assigned_to" name="assigned_to" placeholder="User ID Penanggung Jawab" required>
        
        <label for="deadline">Deadline:</label>
        <input type="date" id="deadline" name="deadline" required>
        
        <button type="submit" name="tambah_task">Tambah Task</button>
    </form>
    
    <a href="index.php?route=project_tasks&project_id=<?= htmlspecialchars($project_id); ?>">← Kembali</a>
</body>
</html>