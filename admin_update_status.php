<?php
session_start();
require "db.php";

if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 3) {
    header("Location: index.php");
    exit;
}

$request_id = $_POST["request_id"];
$status = $_POST["status"];

$stmt = $conn->prepare("
    UPDATE vb_request
    SET status = ?
    WHERE id = ?
");

$stmt->bind_param("si", $status, $request_id);
$stmt->execute();

header("Location: members.php");
exit;