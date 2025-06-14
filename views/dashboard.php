<?php
// FILE: views/dashboard.php

$projects = getProjectsByUserId($_SESSION['user_id'], $pdo);
$message = '';
if (isset($_GET['success']) && $_GET['success'] == 'project_added') {
    $message = '<div class="alert alert-success text-center fw-semibold">✅ Proyek baru berhasil ditambahkan!</div>';
}

$totalTask = getTotalTaskByUser($_SESSION['user_id'], $pdo);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - WORKDIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="mb-4 p-4 bg-white rounded shadow-sm text-center">
            <h1 class="mb-2 fw-bold text-primary">Dashboard</h1>
            <p class="text-muted mb-1">Selamat datang, <span class="fw-semibold">User ID:</span>
                <?= htmlspecialchars($_SESSION['user_id']); ?></p>
            <?= $message ?>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="index.php?route=tambah_project" class="btn btn-info me-2">
                    ➕ Tambah Proyek
                </a>
                <a href="index.php?route=logout" class="btn btn-outline-danger">
                    🚪 Logout
                </a>
            </div>
            <div class="bg-white p-3 rounded shadow-sm border">
                <strong>Total Tugas Anda:</strong> <?= htmlspecialchars($totalTask); ?>
            </div>
        </div>

        <div class="bg-white p-4 rounded shadow-sm mb-4">
            <h2 class="mb-3 text-primary">📁 Daftar Proyek Anda</h2>

            <?php if (count($projects) === 0): ?>
                <div class="alert alert-warning">
                    Anda belum memiliki proyek. Silakan
                    <a href="index.php?route=tambah_project" class="alert-link">tambah proyek baru</a>.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($projects as $project): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-2">
                                        <a href="index.php?route=project_tasks&project_id=<?= htmlspecialchars($project['id']); ?>"
                                            class="text-decoration-none text-primary">
                                            <?= htmlspecialchars($project['nama_projek']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text small mb-1"><strong>Deskripsi:</strong><br>
                                        <?= htmlspecialchars($project['deskripsi']); ?>
                                    </p>
                                    <p class="card-text text-muted small"><strong>Deadline:</strong>
                                        <?= htmlspecialchars($project['deadline']); ?>
                                    </p>
                                    <form action="index.php?route=hapus_project" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus proyek ini?');" class="mt-auto">
                                        <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['id']); ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            🗑 Hapus Proyek
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>