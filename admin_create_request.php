<?php
session_start();
require "db.php";

if (!isset($_SESSION["admin"]) || $_SESSION["admin"] != 3) {
    header("Location: index.php");
    exit;
}

$student_id = $_POST["student_id"];
$date = $_POST["date"];

$dateObj = new DateTime($date);
$month = $dateObj->format("n"); // 1–12

if ($month >= 9 && $month <= 12) {
    $semester = "1";
} else {
    $semester = "2";
}

$stmt = $conn->prepare("
    INSERT INTO vb_request (student_id, date, status, created_at, semester)
    VALUES (?, ?, 'pending', NOW(), ?)
");

$stmt->bind_param("iss", $student_id, $date, $semester);
$stmt->execute();

header("Location: members.php");
exit;