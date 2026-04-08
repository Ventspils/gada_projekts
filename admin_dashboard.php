<?php
require "db.php";

/* VISI REQUESTI */

$result = $conn->query("
    SELECT r.id, r.student_id, r.date, r.status,
           u.fname
    FROM vb_request r
    JOIN vb_users u ON r.student_id = u.id_users
    ORDER BY r.created_at DESC
");

/* VISI STUDENTI dropdownam */

$students = $conn->query("
    SELECT id_users, fname FROM vb_users ORDER BY fname ASC
");
?>

<h2>Admin panelis</h2>

<!-- IZVEIDOT REQUEST -->
<div class="container mt-4">
    <div class="card mt-3">
        <div class="card-body">
            <h5>Izveidot labbūtības dienu skolēnam</h5>

            <form action="admin_create_request.php" method="post">
                <div class="mb-2">
                    <select name="student_id" class="form-select" required>
                        <option value="">Izvēlies skolēnu</option>
                        <?php while ($s = $students->fetch_assoc()): ?>
                            <option value="<?= $s["id_users"] ?>">
                                <?= htmlspecialchars($s["fname"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-2">
                    <input type="date" name="date" class="form-control" required>
                </div>

                <button class="btn btn-primary">Izveidot</button>
            </form>
        </div>
    </div>

<!-- VISI REQUESTI -->

    <div class="card mt-3">
        <div class="card-body">

            <h5>Visi pieprasījumi</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Skolēns</th>
                            <th>Datums</th>
                            <th>Statuss</th>
                            <th>Darbības</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["fname"]) ?></td>
                            <td><?= $row["date"] ?></td>

                            <td>
                                <form action="admin_update_status.php" method="post" class="d-flex gap-2">
                                    <input type="hidden" name="request_id" value="<?= $row["id"] ?>">

                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="pending" <?= $row["status"] == "pending" ? "selected" : "" ?>>Gaida</option>
                                        <option value="approved" <?= $row["status"] == "approved" ? "selected" : "" ?>>Apstiprināts</option>
                                        <option value="rejected" <?= $row["status"] == "rejected" ? "selected" : "" ?>>Noraidīts</option>
                                    </select>

                                    
                                </form>
                            </td>

                            <td>
                                <form action="update_request.php" method="post" style="display:inline;">
                                    <input type="hidden" name="request_id" value="<?= $row["id"] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="btn btn-secondary btn-sm">Dzēst</button>
                                </form>
                            </td>

                        </tr>
                    <?php endwhile; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>