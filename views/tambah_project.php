<form method="POST" action="../controllers/projectControler.php">
    <input type="text" name="nama" placeholder="Nama Projek" required><br>
    <textarea name="deskripsi" placeholder="Deskripsi"></textarea><br>
    <input type="date" name="deadline" required><br>
    <button type="submit" name="tambah_project">Tambah Proyek</button>
</form>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Proyek</title>
    <style>
        body { font-family: Arial; margin: 2rem; background: #f9f9f9; }
        form { max-width: 400px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, textarea, button { width: 100%; margin-top: 10px; padding: 10px; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <?php session_start(); if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; } ?>
    <form method="POST" action="../controllers/projectControler.php">
        <h2>Tambah Proyek</h2>
        <input type="text" name="nama" placeholder="Nama Proyek" required>
        <textarea name="deskripsi" placeholder="Deskripsi Proyek" rows="4"></textarea>
        <input type="date" name="deadline" required>
        <button type="submit" name="tambah_project">Tambah</button>
    </form>
</body>
</html>
