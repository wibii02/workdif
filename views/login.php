<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - WORKDIFF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-primary bg-gradient d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-lg border-0 p-4" style="max-width: 400px; width: 100%; border-radius: 1rem;">
        <div class="text-center mb-3">
            <h4 class="text-primary fw-bold">Masuk ke WORKDIF</h4>
            <p class="text-muted small">Silakan login untuk melanjutkan</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center">
                <?= ($_GET['error'] == 'db_error' && isset($_GET['msg']))
                    ? "Terjadi kesalahan database: " . htmlspecialchars($_GET['msg'])
                    : "Email atau password salah."; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?route=login">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email" required>
            </div>
            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Masukkan password" required>
                </div>
            </div>
            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-primary">Masuk</button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>