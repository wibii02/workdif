<?php
// FILE: views/project_tasks.php

// Komentar ini menunjukkan bahwa inisialisasi sudah dilakukan di public/index.php
// session_start(); 
// if (!isset($_SESSION['user_id'])) { 
//     header("Location: login.php"); 
//     exit; 
// }
// require_once '../config/database.php';
// require_once '../models/Project.php'; 
// require_once '../models/Task.php'; 

// Variabel $pdo dan $_SESSION sudah tersedia di sini karena di-include oleh public/index.php

$project_id = $_GET['project_id'] ?? null;

if (!$project_id) {
    header("Location: index.php?route=dashboard&error=project_id_missing");
    exit;
}

// Ambil detail proyek
$project = getProjectById($project_id, $pdo); 

// Periksa apakah proyek ditemukan dan user memiliki akses
if (!$project || $project['user_id'] !== $_SESSION['user_id']) { 
    header("Location: index.php?route=dashboard&error=access_denied_project");
    exit;
}

// Ambil semua tugas untuk proyek ini
$tasks = getTasksByProjectId($project_id, $pdo); 

// ============== BARIS PHP BARU DI SINI UNTUK MENGAMBIL HITUNGAN ==============
// Ambil jumlah tugas yang belum selesai untuk proyek ini, ditugaskan ke user yang login
$pendingTasksCount = getPendingTasksCountForProjectAndUser($project_id, $_SESSION['user_id'], $pdo);
// ==============================================================================

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas Proyek: <?= htmlspecialchars($project['nama_projek']); ?></title>
<style>
            /* General Body Styling */
body { 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 2rem;
    background-color: #f4f7f6;
    color: #333;
    line-height: 1.6;
}

.container {
    max-width: 1000px;
    margin: 2rem auto;
    background: #fff;
    padding: 2.5rem;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

/* Headings */
h1 {
    font-size: 2.5em;
    color: #2c3e50;
    margin-bottom: 0.5em;
    border-bottom: 2px solid #eee;
    padding-bottom: 0.3em;
}
h2 {
    font-size: 1.8em;
    color: #34495e;
    margin-top: 1.5em;
    margin-bottom: 1em;
}
h3 {
    font-size: 1.4em;
    color: #555;
    margin-bottom: 0.8em;
}

/* Buttons & Links */
a.button, button {
    display: inline-block;
    margin: 10px 10px 20px 0;
    text-decoration: none;
    background: #007bff; /* Primary Blue */
    color: white;
    padding: 12px 25px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-size: 1em;
    transition: background-color 0.3s ease;
}
a.button:hover, button:hover {
    background: #0056b3; /* Darker blue on hover */
}
a.button.add-task { /* Specific style for Add Task button */
    background: #28a745; /* Green */
}
a.button.add-task:hover {
    background: #218838; /* Darker green */
}
p a { /* For links within paragraphs, e.g., "Kembali ke Dashboard" */
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}
p a:hover {
    text-decoration: underline;
}


/* Project Detail Card */
.project-detail { 
    background: #e9f5ff; 
    padding: 1.5rem; 
    border-left: 5px solid #007bff; 
    margin-bottom: 2.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}
.project-detail h3 {
    color: #0056b3;
    margin-top: 0;
    margin-bottom: 15px;
}
.project-detail p {
    margin-bottom: 0.5em;
    color: #444;
}
.project-detail p strong {
    color: #333;
}

/* Summary Box for Pending Tasks */
.summary-box {
    background-color: #fff8e1; /* Light yellow */
    border: 1px solid #ffecb3; /* Yellow border */
    padding: 1rem 1.5rem;
    margin-bottom: 2rem;
    border-radius: 8px;
    font-size: 1.1em;
    color: #665c3b;
    font-weight: bold;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}


/* Table Styling */
table { 
    border-collapse: collapse; 
    width: 100%; 
    margin-top: 2rem; 
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.07);
    border-radius: 8px;
    overflow: hidden; /* Ensures rounded corners apply to content */
}
th, td { 
    padding: 12px 18px; 
    border: 1px solid #e0e0e0; 
    text-align: left; 
}
th { 
    background: #f8f8f8; 
    font-weight: bold;
    color: #555;
    text-transform: uppercase;
    font-size: 0.9em;
}
tbody tr:nth-child(even) { /* Zebra striping */
    background-color: #fcfcfc;
}
tbody tr:hover {
    background-color: #f1f1f1;
    transition: background-color 0.2s ease;
}

/* Status Badges/Labels */
.status-todo { 
    color: #e67e22; /* Orange */
    font-weight: bold; 
    background-color: #fff3e0; /* Light orange background */
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
}
.status-done { 
    color: #27ae60; /* Green */
    font-weight: bold; 
    background-color: #e8f5e9; /* Light green background */
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
}
.status-inprogress { 
    color: #3498db; /* Blue */
    font-weight: bold; 
    background-color: #e3f2fd; /* Light blue background */
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
}

/* Action Buttons within Table */
.action-form { 
    display: inline-block; /* Agar tombol aksi berada di baris yang sama */
    margin: 0 5px; /* Sedikit jarak antar tombol aksi */
}
.action-button { 
    background: #007bff; 
    color: white; 
    border: none; 
    padding: 6px 12px; 
    border-radius: 4px; 
    cursor: pointer; 
    font-size: 0.85em;
    transition: background-color 0.2s ease;
}
.action-button.done { 
    background: #28a745; /* Hijau untuk 'Selesaikan' */
}
.action-button.done:hover {
    background: #218838;
}
.action-button.delete { /* Contoh jika ada tombol delete */
    background: #dc3545; 
}
.action-button.delete:hover {
    background: #c82333;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    body {
        padding: 1rem;
    }
    .container {
        padding: 1.5rem;
        margin: 1rem auto;
    }
    h1 {
        font-size: 2em;
    }
    table, th, td {
        display: block; /* Stack table elements on small screens */
        width: 100%;
    }
    th {
        text-align: right;
        padding-right: 20px;
        background: #f0f0f0; /* Maintain header background */
    }
    td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }
    td::before { /* Add pseudo-elements to display column headers */
        content: attr(data-label);
        position: absolute;
        left: 6px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        text-align: left;
        font-weight: bold;
        color: #555;
    }
}
</style>
</head>
<body>
    <div class="container">
        <h1>Tugas Proyek: <?= htmlspecialchars($project['nama_projek']); ?></h1>
        <p><a href="index.php?route=dashboard">← Kembali ke Dashboard Proyek</a></p>

        <div class="project-detail">
            <h3>Detail Proyek</h3>
            <p><strong>Deskripsi:</strong> <?= htmlspecialchars($project['deskripsi']); ?></p>
            <p><strong>Deadline Proyek:</strong> <?= htmlspecialchars($project['deadline']); ?></p>
        </div>

        <a href="index.php?route=tambah_task&project_id=<?= htmlspecialchars($project['id']); ?>" class="button add-task">➕ Tambah Task untuk Proyek Ini</a>

        <div class="summary-box">
            <strong>Tugas Belum Selesai Saya:</strong> <?= $pendingTasksCount; ?>
        </div>
                <h2>Daftar Tugas</h2>

        <?php if (count($tasks) === 0): ?>
            <p>Belum ada tugas untuk proyek ini. Silakan 
               <a href="index.php?route=tambah_task&project_id=<?= htmlspecialchars($project['id']); ?>">tambah task baru</a>.
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Judul Tugas</th>
                        <th>Deskripsi</th>
                        <th>Ditugaskan Kepada</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td data-label="Judul Tugas"><?= htmlspecialchars($task['judul']); ?></td>
                            <td data-label="Deskripsi"><?= htmlspecialchars($task['deskripsi']); ?></td>
                            <td data-label="Ditugaskan Kepada"><?= htmlspecialchars($task['assigned_to']); ?></td> 
                            <td data-label="Deadline"><?= htmlspecialchars($task['deadline']); ?></td>
                            <td data-label="Status"><span class="status-<?= htmlspecialchars($task['status']); ?>"><?= htmlspecialchars(ucfirst($task['status'])); ?></span></td>
                            <td data-label="Aksi">
                                <?php if ($task['status'] !== 'done'): ?>
                                    <form action="index.php?route=tambah_task" method="POST" class="action-form">
                                        <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']); ?>">
                                        <input type="hidden" name="project_id_redirect" value="<?= htmlspecialchars($project['id']); ?>">
                                        <button type="submit" name="selesaikan_task" class="action-button done">Selesaikan</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>