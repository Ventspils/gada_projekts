<?php
header('Content-Type: application/json');
session_start();
include('db.php');

$vards = $_POST['vards'] ?? '';
$uzvards = $_POST['uzvards'] ?? '';
$email = $_POST['email'] ?? '';
$parole = $_POST['password'] ?? '';
$klase = $_POST['klase'] ?? '';

if (empty($vards) || empty($uzvards) || empty($email) || empty($parole) || empty($klase)) {
    echo json_encode(["success" => false, "message" => "Lūdzu aizpildi visus laukus."]);
    exit;
}

$vards = mysqli_real_escape_string($mysqli, $vards);
$uzvards = mysqli_real_escape_string($mysqli, $uzvards);
$email = mysqli_real_escape_string($mysqli, $email);
$klase = mysqli_real_escape_string($mysqli, $klase);
$parole = SHA1($parole);

// Pārbaudām, vai e-pasts jau eksistē
$check = $mysqli->query("SELECT id_users FROM vb_users WHERE username='$email'");
if (mysqli_num_rows($check) > 0) {
    echo json_encode(["success" => false, "message" => "Šāds lietotājs jau eksistē."]);
    exit;
}

// Ievietojam lietotāju (type = 0, registration_date = automātiski)
$query = "INSERT INTO vb_users (vards, uzvards, username, password, klase, type)
          VALUES ('$vards', '$uzvards', '$email', '$parole', '$klase', 0)";

if ($mysqli->query($query)) {
    echo json_encode(["success" => true, "message" => "Reģistrācija veiksmīga! Tagad vari pieteikties."]);
} else {
    echo json_encode(["success" => false, "message" => "Kļūda: " . $mysqli->error]);
}
?>