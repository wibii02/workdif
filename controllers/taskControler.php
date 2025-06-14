<?php
// FILE: controllers/TaskController.php

// Komentar ini menunjukkan bahwa inisialisasi sudah dilakukan di public/index.php
// require_once '../config/database.php'; 
// session_start(); 
// require_once '../models/Task.php'; 

// Variabel $pdo dan $_SESSION sudah tersedia di sini karena di-include oleh public/index.php

// Logika untuk menambahkan task baru
if (isset($_POST['tambah_task'])) {
    if (!isset($_SESSION['user_id'])) {
        // PENTING: Arahkan ke rute login, bukan ke login.php langsung
        header("Location: index.php?route=login"); 
        exit;
    }

    $project_id = $_POST['project_id'] ?? null; 
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $assigned_to = $_POST['assigned_to'];
    $status = 'todo'; // Default status
    $deadline = trim($_POST['deadline']);

    if (empty($project_id) || empty($judul) || empty($assigned_to) || empty($deadline)) {
        // PENTING: Redirect ke rute tambah_task dengan pesan error
        header("Location: index.php?route=tambah_task&project_id=" . htmlspecialchars($project_id) . "&error=data_tidak_lengkap");
        exit;
    }

    try {
        $pdo->beginTransaction(); // Mulai transaksi untuk operasi atomik

        $stmt1 = $pdo->prepare("INSERT INTO tasks (project_id, judul, deskripsi, assigned_to, status, deadline) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt1->execute([$project_id, $judul, $deskripsi, $assigned_to, $status, $deadline]);

        $stmt2 = $pdo->prepare("INSERT INTO activity_logs (user_id, aksi) VALUES (?, ?)");
        $aksi = 'Menambahkan task "' . $judul . '" untuk proyek ID ' . $project_id;
        $stmt2->execute([$_SESSION['user_id'], $aksi]); // Log user yang membuat task

        $pdo->commit(); // Commit transaksi jika semua berhasil

        // PENTING: Redirect kembali ke halaman tugas proyek yang relevan melalui router
        header("Location: index.php?route=project_tasks&project_id=" . htmlspecialchars($project_id) . "&success=task_added");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack(); // Rollback transaksi jika ada error
        error_log("Gagal menambahkan task: " . $e->getMessage());
        // PENTING: Redirect ke rute tambah_task dengan pesan error database
        header("Location: index.php?route=tambah_task&project_id=" . htmlspecialchars($project_id) . "&error=db_error&msg=" . urlencode($e->getMessage()));
        exit;
    }
}

// Logika untuk menyelesaikan task
if (isset($_POST['selesaikan_task'])) {
    if (!isset($_SESSION['user_id'])) {
        // PENTING: Arahkan ke rute login
        header("Location: index.php?route=login"); 
        exit;
    }

    $task_id = $_POST['task_id'];
    $project_id_redirect = $_POST['project_id_redirect'] ?? null; // ID proyek untuk redirect kembali

    if (empty($task_id)) {
        // PENTING: Jika task_id kosong, redirect ke dashboard atau project_tasks jika project_id diketahui
        $redirect_url = $project_id_redirect ? "index.php?route=project_tasks&project_id=" . htmlspecialchars($project_id_redirect) . "&error=task_id_missing" : "index.php?route=dashboard&error=task_id_missing";
        header("Location: " . $redirect_url); 
        exit;
    }

    try {
        // CATATAN: Pastikan kolom 'dibuat_pada' di tabel 'tasks' bisa menyimpan timestamp untuk 'selesai pada'
        // Jika 'dibuat_pada' hanya untuk created_at, Anda mungkin perlu kolom 'completed_at'
        $stmt = $pdo->prepare("UPDATE tasks SET status = 'done', dibuat_pada = NOW() WHERE id = ?"); 
        $stmt->execute([$task_id]);

        // PENTING: Redirect kembali ke halaman tugas proyek yang relevan melalui router
        $redirect_url = $project_id_redirect ? "index.php?route=project_tasks&project_id=" . htmlspecialchars($project_id_redirect) . "&success=task_completed" : "index.php?route=dashboard&success=task_completed";
        header("Location: " . $redirect_url);
        exit;
    } catch (Exception $e) {
        error_log("Gagal menyelesaikan task: " . $e->getMessage());
        // PENTING: Redirect dengan pesan error database
        $redirect_url = $project_id_redirect ? "index.php?route=project_tasks&project_id=" . htmlspecialchars($project_id_redirect) . "&error=db_error&msg=" . urlencode($e->getMessage()) : "index.php?route=dashboard&error=db_error&msg=" . urlencode($e->getMessage());
        header("Location: " . $redirect_url);
        exit;
    }
}