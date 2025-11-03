<?php
session_start();
include('db.php'); // pieslēdzies datubāzei

// Iegūstam datus no formas
$vards = $_POST['vards'];
$uzvards = $_POST['uzvards'];
$email = $_POST['email'];
$parole = $_POST['password'];
$klase = $_POST['klase'];

// Drošības nolūkos (vienkāršā versija)
$vards = mysqli_real_escape_string($mysqli, $vards);
$uzvards = mysqli_real_escape_string($mysqli, $uzvards);
$email = mysqli_real_escape_string($mysqli, $email);
$klase = mysqli_real_escape_string($mysqli, $klase);

// Šifrējam paroli
$parole = SHA1($parole);

// Pārbaudām, vai šāds lietotājs jau eksistē
$check = $mysqli->query("SELECT * FROM vb_users WHERE username='$email'");
if (mysqli_num_rows($check) > 0) {
    // Ja epasts jau eksistē
    echo "<script>
        alert('Šāds lietotājs jau eksistē!');
        window.location.href='index.php';
    </script>";
    exit;
}

// Ievietojam jauno lietotāju datubāzē
$query = "INSERT INTO vb_users (vards, uzvards, username, password, klase)
          VALUES ('$vards', '$uzvards', '$email', '$parole', '$klase')";

if ($mysqli->query($query)) {
    // Ja viss OK, atgriežam uz login formu
    echo "<script>
        alert('Reģistrācija veiksmīga! Tagad vari pieteikties.');
        window.location.href='index.php';
    </script>";
} else {
    echo "<script>
        alert('Kļūda reģistrējoties: ".$mysqli->error."');
        window.location.href='index.php';
    </script>";
}
?>