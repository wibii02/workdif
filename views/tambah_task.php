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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title mb-4">Tambah Task</h1>

                <?php if ($projectName): ?>
                    <p>Untuk Proyek: <strong class="text-primary"><?= $projectName; ?></strong></p>
                <?php endif; ?>

                <?php if ($message): ?>
                    <div class="alert <?= strpos($message, 'berhasil') !== false ? 'alert-success' : 'alert-danger' ?>"
                        role="alert">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?route=tambah_task">
                    <input type="hidden" name="project_id" value="<?= htmlspecialchars($project_id); ?>">

                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Task</label>
                        <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul Task"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Task</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
                            placeholder="Deskripsi Task"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">User ID Penanggung Jawab</label>
                        <input type="number" class="form-control" id="assigned_to" name="assigned_to"
                            placeholder="User ID Penanggung Jawab" required>
                    </div>

                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input type="date" class="form-control" id="deadline" name="deadline" required>
                    </div>

                    <button type="submit" name="tambah_task" class="btn btn-success">Tambah Task</button>
                    <a href="index.php?route=project_tasks&project_id=<?= htmlspecialchars($project_id); ?>"
                        class="btn btn-link">← Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>