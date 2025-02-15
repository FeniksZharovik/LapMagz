<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("SELECT title, file_content FROM magazines WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($title, $fileContent);
    $stmt->fetch();

    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=\"$title.pdf\"");
    echo $fileContent;
}
?>
