<?php
// require_once '../config/database.php'; // Sudah di-require di public/index.php

// Fungsi untuk mengambil semua proyek berdasarkan user_id
function getProjectsByUserId($user_id, $pdo) {
    try {
        // Query untuk mengambil proyek yang dibuat oleh user atau yang ditugaskan kepada user
        // Sesuaikan dengan struktur tabel 'projects' Anda dan bagaimana Anda mengaitkan user_id
        // Asumsi kolom 'user_id' di tabel 'projects' adalah pembuat proyek
        $stmt = $pdo->prepare("SELECT id, nama_projek, deskripsi, deadline, user_id FROM projects WHERE user_id = ? ORDER BY deadline ASC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting projects by user ID: " . $e->getMessage());
        return [];
    }
}

// Fungsi untuk mengambil satu proyek berdasarkan project_id
function getProjectById($project_id, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT id, nama_projek, deskripsi, deadline, user_id FROM projects WHERE id = ?");
        $stmt->execute([$project_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting project by ID: " . $e->getMessage());
        return null;
    }
}

// Pastikan stored procedure TambahProjek dipanggil di ProjectController.php
// atau jika Anda ingin fungsi PHP, ubah ProjectController.php untuk memanggil fungsi ini
/*
function addProject($nama, $deskripsi, $user_id, $deadline, $pdo) {
    try {
        $stmt = $pdo->prepare("INSERT INTO projects (nama_projek, deskripsi, user_id, deadline) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nama, $deskripsi, $user_id, $deadline]);
    } catch (PDOException $e) {
        error_log("Error adding project: " . $e->getMessage());
        return false;
    }
}
*/
?>