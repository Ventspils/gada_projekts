<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require "db.php";

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}

$student_id = $_SESSION["id"];
$selected_date = $_POST["selected_date"] ?? null;

if (!$selected_date) {
    header("Location: members.php");
    exit;
}

/* Pārbaude - vai jau nav aktīva pieteikuma */

$stmt = $conn->prepare("
    SELECT id FROM vb_request
    WHERE student_id = ?
    AND (status = 'pending' OR status = 'approved')
");

$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // jau ir aktīvs
    header("Location: members.php");
    exit;
}

/* Ievietojam jauno pieteikumu */

$stmt = $conn->prepare("
    INSERT INTO vb_request (student_id, date, status, created_at)
    VALUES (?, ?, 'pending', NOW())
");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("is", $student_id, $selected_date);
$stmt->execute();

/* Atpakaļ uz dashboard */

header("Location: members.php");
exit;