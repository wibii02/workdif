<?php
// FILE: controllers/AuthController.php

// Komentar ini menunjukkan bahwa inisialisasi sudah dilakukan di public/index.php
// require_once '../config/database.php'; 
// session_start(); 

// Pastikan $pdo dan $_SESSION sudah tersedia dari public/index.php
// require_once '../models/User.php'; 

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password']; 

    if (empty($email) || empty($password)) {
        header("Location: index.php?route=login&error=1"); 
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, role, email FROM users WHERE email = ? AND password = ?"); 
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC); 

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // AKTIFKAN KEMBALI REDIRECT INI
            header("Location: index.php?route=dashboard"); 
            exit; 
        } else {
            // AKTIFKAN KEMBALI REDIRECT INI
            header("Location: index.php?route=login&error=1"); 
            exit; 
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        // AKTIFKAN KEMBALI REDIRECT INI
        header("Location: index.php?route=login&error=db_error&msg=" . urlencode($e->getMessage()));
        exit; 
    }
    // HAPUS ATAU KOMENTARI exit; ini jika sebelumnya ada di luar try-catch
    // exit; 
}
?>