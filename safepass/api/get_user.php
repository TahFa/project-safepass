<?php
include '../koneksi.php';

$data = json_decode(file_get_contents("php://input"), true);

// validasi
if (!isset($data['username'])) {

    echo json_encode([
        "success" => false,
        "message" => "Username wajib diisi"
    ]);

    exit;
}

$username = trim($data['username']);

$stmt = $conn->prepare("
    SELECT id, username, password_hash, salt, iterations
    FROM users
    WHERE username = ?
");

$stmt->bind_param("s", $username);

$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    echo json_encode([
        "success" => true,
        "data" => $row
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "User tidak ditemukan"
    ]);
}

$stmt->close();
$conn->close();
?>