<?php
// FILE: models/Task.php

// Fungsi yang sudah ada
function getTotalTaskByUser($user_id, $pdo) {
    $stmt = $pdo->prepare("SELECT TotalTaskUser(:user_id) AS total");
    $stmt->execute(['user_id' => $user_id]);
    $row = $stmt->fetch();
    return $row['total'];
}

function getCompletedTasksByUser($user_id, $pdo) {
    $stmt = $pdo->prepare("SELECT t.*, p.nama_projek 
                             FROM tasks t 
                             JOIN projects p ON t.project_id = p.id
                             WHERE t.assigned_to = ? AND t.status = 'done' ORDER BY t.dibuat_pada DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi BARU: Ambil semua tugas untuk proyek tertentu
function getTasksByProjectId($project_id, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT id, project_id, judul, deskripsi, assigned_to, status, deadline FROM tasks WHERE project_id = ? ORDER BY deadline ASC");
        $stmt->execute([$project_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting tasks by project ID: " . $e->getMessage());
        return [];
    }
}

// ============== FUNGSI BARU UNTUK MENGHITUNG TUGAS BELUM SELESAI ==============
function getPendingTasksCountForProjectAndUser($project_id, $user_id, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(id) AS pending_count 
                               FROM tasks 
                               WHERE project_id = ? 
                                 AND assigned_to = ? 
                                 AND status != 'done'"); // status bukan 'done'
        $stmt->execute([$project_id, $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['pending_count'] ?? 0; // Mengembalikan 0 jika tidak ada hasil
    } catch (PDOException $e) {
        error_log("Error getting pending tasks count for project and user: " . $e->getMessage());
        return 0; // Mengembalikan 0 jika ada error
    }
}
// ==============================================================================
// ==============================================================================

?>