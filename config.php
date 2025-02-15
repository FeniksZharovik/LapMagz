<?php
if (!session_id()) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "majalah_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
