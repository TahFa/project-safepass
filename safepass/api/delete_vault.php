<?php
session_start();

include "../koneksi.php";

header("Content-Type: application/json");


// =========================
// CEK LOGIN
// =========================
if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);

    exit;
}


// =========================
// AMBIL JSON
// =========================
$data = json_decode(
    file_get_contents("php://input"),
    true
);


// =========================
// VALIDASI
// =========================
if (
    !isset($data['ciphertext']) ||
    !isset($data['iv']) ||
    !isset($data['salt'])
) {

    echo json_encode([
        "success" => false,
        "message" => "Data vault tidak lengkap"
    ]);

    exit;
}


$user_id =
    $_SESSION['user_id'];

$ciphertext =
    $data['ciphertext'];

$iv =
    $data['iv'];

$salt =
    $data['salt'];


// =========================
// UPDATE VAULT
// =========================
$stmt = $conn->prepare("
    UPDATE vaults
    SET
        ciphertext = ?,
        iv = ?,
        salt = ?
    WHERE user_id = ?
");

$stmt->bind_param(
    "sssi",
    $ciphertext,
    $iv,
    $salt,
    $user_id
);


// =========================
// EXECUTE
// =========================
if ($stmt->execute()) {

    echo json_encode([
        "success" => true
    ]);
} else {

    echo json_encode([
        "success" => false,
        "message" => "Gagal hapus vault"
    ]);
}

$stmt->close();
$conn->close();
?>