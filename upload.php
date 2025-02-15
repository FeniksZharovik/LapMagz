<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] != 0) {
        $error = "Terjadi kesalahan saat mengunggah file.";
    } else {
        $title = $_POST['title'];
        $pdfData = file_get_contents($_FILES['pdf_file']['tmp_name']);
        $user_id = $_SESSION['user_id']; // Ambil user ID dari session

        $stmt = $conn->prepare("INSERT INTO magazines (title, file_content, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sbi", $title, $pdfData, $user_id);

        if ($stmt->execute()) {
            $success = "Majalah berhasil diunggah!";
        } else {
            $error = "Gagal mengunggah majalah.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Upload Majalah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Majalah Online</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <!-- Form Upload -->
    <div class="container mt-4">
        <h3 class="mb-4">Upload Majalah/Buletin</h3>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Judul Majalah</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="pdf_file" class="form-label">File PDF</label>
                <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept="application/pdf" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

</body>
</html>
