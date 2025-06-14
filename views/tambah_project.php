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
    <style>
        body { font-family: Arial; margin: 2rem; background: #f9f9f9; }
        form { max-width: 400px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="date"], textarea {
            width: calc(100% - 22px); /* Penyesuaian agar padding tidak membuat lebar over */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button { 
            background: #007bff; 
            color: white; 
            border: none; 
            cursor: pointer; 
            padding: 10px 15px; /* Penyesuaian padding button */
            border-radius: 4px; /* Penyesuaian border-radius button */
            font-size: 1em; /* Penyesuaian font-size button */
            width: auto; /* Agar tidak 100% jika tidak diinginkan */
        }
        button:hover { background-color: #0056b3; }
        a.back-link { display: inline-block; margin-top: 15px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <h1>Tambah Proyek Baru</h1>

    <?php echo $message; // Tampilkan pesan error/success di sini ?>

    <form method="POST" action="index.php?route=tambah_project">
        <label for="nama">Nama Proyek:</label>
        <input type="text" id="nama" name="nama" placeholder="Nama Proyek" required>
        
        <label for="deskripsi">Deskripsi Proyek:</label>
        <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsi Proyek" rows="4"></textarea>
        
        <label for="deadline">Deadline Proyek:</label>
        <input type="date" id="deadline" name="deadline" required>
        
        <button type="submit" name="tambah_project">Tambah</button>
    </form>
    
    <a href="index.php?route=dashboard" class="back-link">← Kembali ke Dashboard</a>
</body>
</html>