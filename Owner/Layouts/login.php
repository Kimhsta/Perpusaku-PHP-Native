<?php
include '../../config/koneksi.php'; // Menghubungkan ke database menggunakan PDO

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Query untuk validasi login owner menggunakan PDO
        $query = "SELECT id_owner, nama_pemilik FROM owner WHERE username = :username AND password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Login berhasil
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            session_start();
            $_SESSION['id_owner'] = $data['id_owner']; // Simpan ID owner di sesi
            $_SESSION['nama_pemilik'] = $data['nama_pemilik']; // Simpan nama pemilik di sesi
            header('Location: ../Dashboard/dashboard.php'); // Redirect ke dashboard owner
            exit;
        } else {
            // Login gagal
            $error = "Username atau password salah.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2 class="fw-bold text-center">Login Owner</h2>
            <p class="text-center mb-5">Masukkan username dan password Anda untuk masuk.</p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center">
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username Anda" required>
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password Anda" required>
                    <i class="bi bi-eye-slash password-toggle" id="togglePassword"></i>
                </div>
                <button type="submit" class="mt-3 btn btn-primary w-100">Login</button>
            </form>
        </div>
        <div class="graphic-side">
            <img src="../../Assets/img/logo.png" alt="Logo" class="img-fluid logo-style">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../Assets/js/login_Admin.js"></script>
</body>
</html>
