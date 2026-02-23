<?php
session_start();

if (!isset($_SESSION["id"]) || $_SESSION["id"] <= 0) {
    header("Location: index.php");
    exit;
}

$type = $_SESSION["type"];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vents</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>
<body>

<div class="container mt-4">
    <?php
    if ($type === "student") {
        include "student_dashboard.php";
    } elseif ($type === "teacher") {
        include "teacher_dashboard.php";
    }
    ?>
</div>

</body>
</html>