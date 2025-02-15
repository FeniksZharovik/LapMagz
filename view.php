<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("SELECT file_content FROM magazines WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($fileContent);
    $stmt->fetch();
    $stmt->close();

    if ($fileContent) {
        header("Content-type: application/pdf");
        echo $fileContent;
    } else {
        echo "File tidak ditemukan.";
    }
} else {
    echo "Akses tidak valid.";
}
?>
