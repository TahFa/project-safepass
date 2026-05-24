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
        "message" => "Data Vault tidak lengkap"
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
// CEK VAULT USER
// =========================
$check = $conn->prepare("
    SELECT id
    FROM vaults
    WHERE user_id = ?
");

$check->bind_param(
    "i",
    $user_id
);

$check->execute();

$result =
    $check->get_result();


// =========================
// UPDATE / INSERT
// =========================
if ($result->num_rows > 0) {

    // UPDATE
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
} else {

    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO vaults(
            user_id,
            ciphertext,
            iv,
            salt
        )
        VALUES(?,?,?,?)
    ");

    $stmt->bind_param(
        "isss",
        $user_id,
        $ciphertext,
        $iv,
        $salt
    );
}


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
        "message" => "Gagal simpan vault"
    ]);
}


$stmt->close();

$conn->close();
?>