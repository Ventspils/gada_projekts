<?php
session_start();
require "db.php";

$request_id = $_POST["request_id"] ?? null;
$action = $_POST["action"] ?? null;

if (!$request_id || !$action) {
    header("Location: members.php");
    exit;
}

/* STUDENTS VAR ATCELT */

if ($action === "cancel") {

    if ($_SESSION["type"] !== "student") {
        header("Location: members.php");
        exit;
    }

    $stmt = $conn->prepare("
        UPDATE vb_request 
        SET status = 'cancel' 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    header("Location: members.php");
    exit;
}

/* SKOLOTĀJS VAR APSTRĀDĀT */

if ($_SESSION["type"] !== "teacher") {
    header("Location: members.php");
    exit;
}

/* DELETE */

if ($action === "delete") {
    $stmt = $conn->prepare("DELETE FROM vb_request WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    header("Location: members.php");
    exit;
}

/* APPROVE / REJECT */

if ($action === "approve" || $action === "reject") {

    $status = ($action === "approve") ? "approved" : "rejected";

    $stmt = $conn->prepare("
        UPDATE vb_request
        SET status = ?
        WHERE id = ?
    ");
    $stmt->bind_param("si", $status, $request_id);
    $stmt->execute();
}

header("Location: members.php");
exit;