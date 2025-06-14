<?php
// FILE: controllers/AuthController.php

// require_once '../config/database.php'; // Sudah dihandle oleh public/index.php
// session_start(); // Sudah dihandle oleh public/index.php

// Pastikan $pdo dan $_SESSION sudah tersedia dari public/index.php
// Jika Anda memiliki model User.php dengan fungsi login, Anda bisa memanggilnya di sini
// require_once '../models/User.php'; // Opsional, tergantung implementasi login Anda

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Catatan: Menyimpan password plaintext TIDAK disarankan. Gunakan password_hash() dan password_verify() untuk keamanan.

    if (empty($email) || empty($password)) {
        // PENTING: Redirect ke rute login dengan pesan error
        header("Location: index.php?route=login&error=1"); 
        exit;
    }

    try {
        // Asumsi $pdo tersedia dari public/index.php
        // CATATAN KEAMANAN: Membandingkan password secara langsung seperti ini sangat tidak aman.
        // Anda HARUS menggunakan password hashing (password_hash() saat daftar, password_verify() saat login).
        $stmt = $pdo->prepare("SELECT id, role FROM users WHERE email = ? AND password = ?"); 
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Gunakan FETCH_ASSOC untuk hasil array asosiatif
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // PENTING: Redirect ke rute dashboard setelah login berhasil
            header("Location: index.php?route=dashboard"); 
            exit;
        } else {
            // PENTING: Redirect ke rute login dengan pesan error jika kredensial salah
            header("Location: index.php?route=login&error=1"); 
            exit;
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        // Redirect ke login dengan pesan error umum jika ada masalah database
        header("Location: index.php?route=login&error=db_error&msg=" . urlencode($e->getMessage()));
        exit;
    }
}

// Catatan: Anda mungkin perlu rute 'logout' di public/index.php yang memanggil session_destroy()
// dan redirect ke index.php?route=login
?>