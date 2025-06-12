<?php
require_once '../config/database.php';
session_start();

if (isset($_POST['tambah_project'])) {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("CALL TambahProjek(?, ?, ?, ?)");
    $stmt->execute([$nama, $deskripsi, $user_id, $deadline]);

    header("Location: ../views/dashboard.php");
}
