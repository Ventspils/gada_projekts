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