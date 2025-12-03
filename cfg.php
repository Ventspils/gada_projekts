<?php
session_start();
$code = $_POST['pass'];
$code = SHA1($code);
include('db.php');
$result = $mysqli->query("SELECT * FROM vb_users WHERE password='$parole'");
if (mysqli_num_rows($result) > 0){
    while ($row = $result ->fetch_assoc()){
       $_SESSION["id"] = $row['id_users'];
    }
    header("Location: members.php");
}
else{
    header("Location: index.php");
}

?>