<?php
// FILE: views/project_tasks.php

$project_id = $_GET['project_id'] ?? null;

if (!$project_id) {
    header("Location: index.php?route=dashboard&error=project_id_missing");
    exit;
}

$project = getProjectById($project_id, $pdo);

if (!$project || $project['user_id'] !== $_SESSION['user_id']) {
    header("Location: index.php?route=dashboard&error=access_denied_project");
    exit;
}

$tasks = getTasksByProjectId($project_id, $pdo);
$pendingTasksCount = getPendingTasksCountForProjectAndUser($project_id, $_SESSION['user_id'], $pdo);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tugas Proyek: <?= htmlspecialchars($project['nama_projek']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="mb-4">
            <h1 class="text-primary">Tugas Proyek: <?= htmlspecialchars($project['nama_projek']); ?></h1>
            <a href="index.php?route=dashboard" class="btn btn-link">← Kembali ke Dashboard Proyek</a>
        </div>

        <div class="card mb-4 border-start border-primary border-5 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-primary">Detail Proyek</h5>
                <p class="mb-1"><strong>Deskripsi:</strong> <?= htmlspecialchars($project['deskripsi']); ?></p>
                <p class="mb-0"><strong>Deadline Proyek:</strong> <?= htmlspecialchars($project['deadline']); ?></p>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="index.php?route=tambah_task&project_id=<?= htmlspecialchars($project['id']); ?>"
                class="btn btn-success">➕ Tambah Task</a>
            <div class="alert alert-warning py-2 px-3 mb-0 fw-semibold">Tugas Belum Selesai Anda:
                <?= $pendingTasksCount; ?></div>
        </div>

        <h4 class="mb-3">Daftar Tugas</h4>

        <?php if (count($tasks) === 0): ?>
            <div class="alert alert-info">Belum ada tugas. <a
                    href="index.php?route=tambah_task&project_id=<?= htmlspecialchars($project['id']); ?>"
                    class="alert-link">Tambah task baru</a>.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered align-middle shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
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
                                <td><?= htmlspecialchars($task['judul']); ?></td>
                                <td><?= htmlspecialchars($task['deskripsi']); ?></td>
                                <td><?= htmlspecialchars($task['assigned_to']); ?></td>
                                <td><?= htmlspecialchars($task['deadline']); ?></td>
                                <td>
                                    <?php
                                    $statusClass = match ($task['status']) {
                                        'done' => 'success',
                                        'inprogress' => 'primary',
                                        default => 'warning'
                                    };
                                    ?>
                                    <span
                                        class="badge bg-<?= $statusClass ?>"><?= htmlspecialchars(ucfirst($task['status'])); ?></span>
                                </td>
                                <td>
                                    <?php if ($task['status'] !== 'done'): ?>
                                        <form action="index.php?route=tambah_task" method="POST" class="d-inline">
                                            <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']); ?>">
                                            <input type="hidden" name="project_id_redirect"
                                                value="<?= htmlspecialchars($project['id']); ?>">
                                            <button type="submit" name="selesaikan_task"
                                                class="btn btn-sm btn-success">Selesaikan</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>