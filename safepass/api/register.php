<?php
header("Content-Type: application/json");

include '../koneksi.php';

$raw = file_get_contents("php://input");

$data = json_decode($raw, true);

if (!$data) {

    echo json_encode([
        "success" => false,
        "message" => "JSON tidak terbaca",
        "raw" => $raw
    ]);

    exit;
}


// VALIDASI
if (
    !isset($data['username']) ||
    !isset($data['password_hash']) ||
    !isset($data['salt']) ||
    !isset($data['iterations'])
) {

    echo json_encode([
        "success" => false,
        "message" => "Data tidak lengkap"
    ]);

    exit;
}


$username = trim($data['username']);
$password_hash = $data['password_hash'];
$salt = $data['salt'];
$iterations = (int)$data['iterations'];


// CHECK USERNAME
$check = $conn->prepare(
    "SELECT id FROM users WHERE username = ?"
);

$check->bind_param("s", $username);

$check->execute();

$result = $check->get_result();

if ($result->num_rows > 0) {

    echo json_encode([
        "success" => false,
        "message" => "Username sudah digunakan"
    ]);

    exit;
}


// INSERT USER
$stmt = $conn->prepare("
    INSERT INTO users
    (username, password_hash, salt, iterations)
    VALUES (?, ?, ?, ?)
");

if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);

    exit;
}


$stmt->bind_param(
    "sssi",
    $username,
    $password_hash,
    $salt,
    $iterations
);


if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Registrasi berhasil"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => $stmt->error
    ]);
}


$stmt->close();

$conn->close();

exit;
?>