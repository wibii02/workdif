<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/database.php';
require_once '../models/Task.php';

// Ambil total dan daftar tugas selesai
$totalTask = getTotalTaskByUser($_SESSION['user_id'], $pdo);
$completedTasks = getCompletedTasksByUser($_SESSION['user_id'], $pdo);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial; margin: 2rem; }
        a { display: inline-block; margin: 10px 10px 20px 0; text-decoration: none; background: #28a745; color: white; padding: 10px 15px; border-radius: 4px; }
        .summary { background: #f0f0f0; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; }
        th { background: #f8f8f8; }
    </style>
</head>
<body>
    <h1>Dashboard</h1>
    <p>Selamat datang, User ID: <?= htmlspecialchars($_SESSION['user_id']); ?></p>

    <a href="tambah_project.php">➕ Tambah Proyek</a>
    <a href="tambah_task.php">➕ Tambah Task</a>

    <div class="summary">
        <strong>Total Tugas Anda:</strong> <?= $totalTask ?>
    </div>

    <h3>✅ Tugas yang Telah Selesai</h3>

    <?php if (count($completedTasks) === 0): ?>
        <p>Tidak ada tugas selesai.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Proyek</th>
                    <th>Judul Tugas</th>
                    <th>Deskripsi</th>
                    <th>Deadline</th>
                    <th>Selesai Pada</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($completedTasks as $task): ?>
                    <tr>
                        <td><?= htmlspecialchars($task['nama_projek']) ?></td>
                        <td><?= htmlspecialchars($task['judul']) ?></td>
                        <td><?= htmlspecialchars($task['deskripsi']) ?></td>
                        <td><?= htmlspecialchars($task['deadline']) ?></td>
                        <td><?= htmlspecialchars($task['dibuat_pada']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
