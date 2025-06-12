<form method="POST" action="../controllers/taskControler.php">
    <input type="hidden" name="project_id" value="1"> <!-- Misalnya dari projek ID 1 -->
    <input type="text" name="judul" placeholder="Judul Task" required>
    <textarea name="deskripsi" placeholder="Deskripsi Task"></textarea>
    <input type="number" name="assigned_to" placeholder="User ID Penanggung Jawab" required>
    <input type="date" name="deadline" required>
    <button type="submit" name="tambah_task">Tambah Task</button>
</form>
