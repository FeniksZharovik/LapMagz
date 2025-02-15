<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Ambil bagian sebelum '@gmail.com'
    $username = explode('@', $email)[0];

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Registrasi gagal!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <h2 class="text-center">Register</h2>
                <form method="POST">
                    <input type="email" name="email" placeholder="Email" required class="form-control mb-2">
                    <input type="password" name="password" placeholder="Password" required class="form-control mb-3">
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
