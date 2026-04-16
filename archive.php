<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "db.php";

$result = $conn->query("
    SELECT r.*, u.fname
    FROM vb_request r
    JOIN vb_users u ON r.student_id = u.id_users
    ORDER BY r.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Arhīvs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tavs CSS (ja ir) -->
    <link rel="stylesheet" href="main.css">
</head>

<body>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Arhīvs</h2>
        <a href="members.php" class="btn btn-secondary">Atpakaļ</a>
    </div>

    <div class="card">
        <div class="card-body">

            <?php if ($result->num_rows > 0): ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Skolēns</th>
                                <th>Pieteiktais datums</th>
                                <th>Statuss</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["fname"]) ?></td>
                                <td><?= htmlspecialchars($row["date"]) ?></td>
                                <td>
                                    <?php if ($row["status"] === "approved"): ?>
                                        <span class="badge bg-success">Apstiprināts</span>

                                    <?php elseif ($row["status"] === "rejected"): ?>
                                        <span class="badge bg-danger">Noraidīts</span>

                                    <?php elseif ($row["status"] === "cancel"): ?>
                                        <span class="badge bg-secondary">Atcelts</span>

                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Gaida</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                        </tbody>
                    </table>
                </div>

            <?php else: ?>

                <div class="alert alert-info">
                    Arhīvā nav ierakstu.
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>