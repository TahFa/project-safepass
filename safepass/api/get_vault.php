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


$user_id =
    $_SESSION['user_id'];


// =========================
// AMBIL VAULT
// =========================
$stmt = $conn->prepare("
    SELECT
        ciphertext,
        iv,
        salt
    FROM vaults
    WHERE user_id = ?
");

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result =
    $stmt->get_result();


// =========================
// RESPONSE
// =========================
if ($row = $result->fetch_assoc()) {

    echo json_encode([
        "success" => true,

        "data" => [

            "ciphertext" =>
            $row['ciphertext'],

            "iv" =>
            $row['iv'],

            "salt" =>
            $row['salt']
        ]
    ]);
} else {

    echo json_encode([
        "success" => false,
        "message" => "Vault kosong"
    ]);
}


$stmt->close();

$conn->close();
?>