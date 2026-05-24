<?php
session_start();

include "../koneksi.php";

header("Content-Type: application/json");

// AMBIL JSON
$data = json_decode(file_get_contents("php://input"), true);

// VALIDASI USERNAME
if (!isset($data['username'])) {
    echo json_encode([
        "success" => false,
        "message" => "Username wajib diisi"
    ]);
    exit;
}

$username = trim($data['username']);

// CARI USER
$stmt = $conn->prepare("
    SELECT id, username
    FROM users
    WHERE username = ?
    LIMIT 1
");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// USER DITEMUKAN
if ($row = $result->fetch_assoc()) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    echo json_encode([
        "success" => true
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "User tidak ditemukan"
    ]);
}

$stmt->close();
$conn->close();