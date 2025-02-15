<?php
session_start();
include 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fungsi untuk membuat preview gambar dari halaman pertama PDF
function generatePDFPreview($pdfData) {
    $tempPdfPath = tempnam(sys_get_temp_dir(), 'pdf') . ".pdf";
    file_put_contents($tempPdfPath, $pdfData);

    $imagePath = $tempPdfPath . ".jpg";
    
    // Path lengkap ke pdftoppm (pastikan sudah diinstal Poppler)
    $popplerPath = "C:\\path\\to\\poppler\\bin\\pdftoppm.exe";
    $cmd = "\"$popplerPath\" -jpeg -f 1 -singlefile \"$tempPdfPath\" \"$tempPdfPath\"";
    
    exec($cmd, $output, $returnVar);
    
    if ($returnVar !== 0) {
        return 'default.jpg'; // Jika gagal, tampilkan gambar default
    }

    return "data:image/jpeg;base64," . base64_encode(file_get_contents($imagePath));
}

// Ambil daftar majalah dari database
$result = $conn->query("SELECT id, title, file_content FROM magazines ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Majalah Online</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">LapMagz</a>
            <div class="d-flex">
                <a href="upload.php" class="btn btn-success me-2">Upload Majalah</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Daftar Majalah -->
    <div class="container mt-4">
        <h3 class="mb-4">Hanya Sekumpulan Majalah dan Buletin</h3>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php $previewImage = generatePDFPreview($row['file_content']); ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="<?= $previewImage ?>" class="card-img-top" alt="Preview">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                            <div class="d-flex justify-content-between">
                                <form action="view.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-primary">Lihat Selengkapnya</button>
                                </form>
                                <form action="download.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-success">Download</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>
