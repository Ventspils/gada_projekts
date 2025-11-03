<?php

$mysqli = mysqli_connect ("localhost","u547027111_mvg","MVGskola1");
mysqli_select_db ($mysqli,"u547027111_mvg");
$mysqli->query ("set character_set_client='utf8'");
$mysqli->query ("set character_set_results='utf8'");
$mysqli->query ("set collation_connection='utf81_general_ci'");
$mysqli->query ("SET NAMES utf8");
?>