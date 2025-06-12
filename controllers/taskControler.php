<?php
require_once '../config/database.php';
session_start();

if (isset($_POST['tambah_task'])) {
    $project_id = $_POST['project_id'];
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $assigned_to = $_POST['assigned_to'];
    $status = 'todo';
    $deadline = $_POST['deadline'];

    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Tambah task
        $stmt1 = $pdo->prepare("INSERT INTO tasks (project_id, judul, deskripsi, assigned_to, status, deadline) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt1->execute([$project_id, $judul, $deskripsi, $assigned_to, $status, $deadline]);

        // Tambah log aktivitas
        $stmt2 = $pdo->prepare("INSERT INTO activity_logs (user_id, aksi) VALUES (?, ?)");
        $aksi = 'Menambahkan task "' . $judul . '"';
        $stmt2->execute([$assigned_to, $aksi]);

        // Commit transaksi
        $pdo->commit();

        header("Location: ../views/dashboard.php?success=1");
    } catch (Exception $e) {
        // Rollback jika gagal
        $pdo->rollBack();
        echo "Gagal menambahkan task: " . $e->getMessage();
    }
    
} 
if (isset($_POST['selesaikan_task'])) {
    $task_id = $_POST['task_id'];

    try {
        $stmt = $pdo->prepare("UPDATE tasks SET status = 'done' WHERE id = ?");
        $stmt->execute([$task_id]);

        header("Location: ../views/dashboard.php?selesai=1");
    } catch (Exception $e) {
        echo "Gagal menyelesaikan task: " . $e->getMessage();
    }
}



