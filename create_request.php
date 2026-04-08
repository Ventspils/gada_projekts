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
/* Nosakām semestri pēc IZVĒLĒTĀ datuma */
$month = date("n", strtotime($selected_date));

    if ($month >= 9 && $month <= 12) {
        $semester = "1";
    } elseif ($month >= 1 && $month <= 5) {
        $semester = "2";
    } else {
        // vasara vai nederīgs datums
        header("Location: members.php");
        exit;
    }

/* Pārbaude - vai jau nav aktīva pieteikuma */

$stmt = $conn->prepare("
    SELECT id FROM vb_request
    WHERE student_id = ?
    AND semester = ?
    AND (status = 'pending' OR status = 'approved')
");

if (!$stmt){
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("is", $student_id, $semester);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // jau ir aktīvs
    header("Location: members.php");
    exit;
}

/* Ievietojam jauno pieteikumu */

$stmt = $conn->prepare("
    INSERT INTO vb_request (student_id, date, status, created_at, semester)
    VALUES (?, ?, 'pending', NOW(), ?)
");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("iss", $student_id, $selected_date, $semester);
$stmt->execute();

/* Atpakaļ uz dashboard */

header("Location: members.php");
exit;
