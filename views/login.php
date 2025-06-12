<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - WORKDIF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/style.css">

</head>
<body>
    <div class="container-login">
        <!-- Kiri: Tulisan tanpa kotak -->
        <div class="left-text">
            Selamat Datang di WORKDIF
        </div>

        <!-- Kanan: Form Login dalam kotak -->
        <div class="login-box">
            <h2 class="text-center mb-4 text-login">LOGIN</h2>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">Email atau password salah.</div>
            <?php endif; ?>

            <form method="POST" action="../controllers/AuthController.php">
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
