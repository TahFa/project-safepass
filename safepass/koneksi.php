<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_safepass";

$conn = mysqli_connect($host, $user, $pass, $db);

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// optional: set charset biar aman (UTF-8)
mysqli_set_charset($conn, "utf8mb4");
?>