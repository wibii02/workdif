<?php
// FILE: views/tambah_project.php

// Komentar ini menunjukkan bahwa inisialisasi sudah dilakukan di public/index.php
// session_start(); 
// if (!isset($_SESSION['user_id'])) { 
//     header("Location: login.php"); 
//     exit; 
// } 

// Menampilkan pesan error atau success dari redirect (jika ada)
$message = '';
if (isset($_GET['success']) && $_GET['success'] == 'project_added') {
    $message = '<p style="color: green;">Proyek berhasil ditambahkan!</p>';
} elseif (isset($_GET['error'])) {
    $error_msg = $_GET['error'];
    if ($error_msg == 'data_tidak_lengkap') {
        $message = '<p style="color: red;">Mohon lengkapi semua bidang yang wajib diisi.</p>';
    } elseif ($error_msg == 'db_error') {
        $message = '<p style="color: red;">Terjadi kesalahan database: ' . htmlspecialchars($_GET['msg'] ?? 'Unknown error') . '</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Proyek Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-4">
    <div class="container">
        <h1 class="mb-4 text-center">Tambah Proyek Baru</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?= strpos($message, 'green') !== false ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                <?= strip_tags($message); ?>
            </div>
        <?php endif; ?>

        <div class="card mx-auto shadow-sm" style="max-width: 500px;">
            <div class="card-body">
                <form method="POST" action="index.php?route=tambah_project">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Proyek</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Proyek" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Proyek</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Deskripsi Proyek" rows="4"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline Proyek</label>
                        <input type="date" id="deadline" name="deadline" class="form-control" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="tambah_project" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="index.php?route=dashboard" class="btn btn-link">← Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
