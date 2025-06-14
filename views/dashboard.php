<?php
// FILE: views/dashboard.php

$projects = getProjectsByUserId($_SESSION['user_id'], $pdo);
$message = '';
if (isset($_GET['success']) && $_GET['success'] == 'project_added') {
    $message = '<p class="text-success fw-semibold">Proyek baru berhasil ditambahkan!</p>';
}

$totalTask = getTotalTaskByUser($_SESSION['user_id'], $pdo);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container py-4">
    <h1 class="mb-3">Dashboard</h1>
    <p class="text-muted">Selamat datang, User ID: <?= htmlspecialchars($_SESSION['user_id']); ?></p>

    <?= $message ?>

    <div class="mb-3 d-flex gap-2">
        <a href="index.php?route=tambah_project" class="btn btn-success">➕ Tambah Proyek</a>
        <a href="index.php?route=logout" class="btn btn-danger">🚪 Logout</a>
    </div>

    <div class="bg-light p-3 rounded mb-4 border">
        <strong>Total Tugas Anda:</strong> <?= htmlspecialchars($totalTask); ?>
    </div>

    <h2 class="mb-3">Daftar Proyek Anda</h2>

    <?php if (count($projects) === 0): ?>
        <div class="alert alert-warning">Anda belum memiliki proyek. Silakan <a href="index.php?route=tambah_project"
                class="alert-link">tambah proyek baru</a>.</div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($projects as $project): ?>
                <div class="col">
                    <div class="card h-100 shadow shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="mb-2">
                                <h5 class="card-title">
                                    <a href="index.php?route=project_tasks&project_id=<?= htmlspecialchars($project['id']); ?>"
                                        class="text-decoration-none">
                                        <?= htmlspecialchars($project['nama_projek']); ?>
                                    </a>
                                </h5>
                                <p class="card-text mb-1"><strong>Deskripsi:</strong>
                                    <?= htmlspecialchars($project['deskripsi']); ?></p>
                                <p class="card-text text-muted"><strong>Deadline:</strong>
                                    <?= htmlspecialchars($project['deadline']); ?></p>
                            </div>
                            <form action="index.php?route=hapus_project" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus proyek ini?');">
                                <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['id']); ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">🗑 Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</body>

</html>