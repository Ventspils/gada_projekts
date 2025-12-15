<?php
session_start();
$code = $_POST['pass'];
$code = SHA1($code);
include('db.php');

$result = $mysqli->query("SELECT * FROM vb_users WHERE code='$code' LIMIT 1");
if ($result && mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();

    $_SESSION["id"] = $row['id_users'];  // student ID column
    $_SESSION["type"] = "student";       // user type

    header("Location: members.php");
    exit;
}

// If not a student, try teacher login
$result = $mysqli->query("SELECT * FROM vb_teachers WHERE code='$code' LIMIT 1");

if ($result && mysqli_num_rows($result) > 0) {
    $row = $result->fetch_assoc();

    $_SESSION["id"] = $row['id'];        // teacher ID column (different name!)
    $_SESSION["type"] = "teacher";       // user type

    header("Location: members.php");
    exit;
}

// If no match at all back to login
header("Location: index.php");
exit;

?>