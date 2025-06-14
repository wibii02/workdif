<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - WORKDIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/style.css"> </head>
<body>
    <div class="container-login">
        <div class="left-text">
            Selamat Datang di WORKDIF
        </div>

        <div class="login-box">
            <h2 class="text-center mb-4 text-login">LOGIN</h2>

            <?php 
            // Tambahkan sedikit perbaikan agar pesan error lebih informatif
            if (isset($_GET['error'])): 
                $errorMessage = "Email atau password salah.";
                if (isset($_GET['error']) && $_GET['error'] == 'db_error' && isset($_GET['msg'])) {
                    $errorMessage = "Terjadi kesalahan database: " . htmlspecialchars($_GET['msg']);
                } else if (isset($_GET['error']) && $_GET['error'] == '1') {
                    $errorMessage = "Email atau password salah.";
                }
            ?>
                <div class="error-message" style="color: red; margin-bottom: 10px; text-align: center;"><?= $errorMessage; ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?route=login"> 
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" name="login" class="btn btn-cyan w-100">Masuk</button>
            </form>
        </div>
    </div>
</body>
</html>