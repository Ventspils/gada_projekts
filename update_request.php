<?php
session_start();
require "db.php";

if ($_SESSION["type"] !== "teacher") {
    header("Location: members.php");
    exit;
}

$request_id = $_POST["request_id"] ?? null;
$action = $_POST["action"] ?? null;

if (!$request_id || !$action) {
    header("Location: members.php");
    exit;
}

if ($_POST["action"] === "delete") {
    $stmt = $conn->prepare("DELETE FROM vb_request WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
}

$status = ($action === "approve") ? "approved" : "rejected";

$stmt = $conn->prepare("
    UPDATE vb_request
    SET status = ?
    WHERE id = ?
");

$stmt->bind_param("si", $status, $request_id);
$stmt->execute();

header("Location: members.php");
exit;
