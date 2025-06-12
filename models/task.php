<?php
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
                           WHERE t.assigned_to = ? AND t.status = 'done'");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
