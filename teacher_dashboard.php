<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "db.php";

$stmt = $conn->prepare("
    SELECT r.id, r.student_id, r.`date`, r.created_at,
           u.fname
    FROM vb_request r
    JOIN vb_users u ON r.student_id = u.id_users
    WHERE r.status = 'pending'
    ORDER BY r.created_at ASC
");

$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Skolotāja panelis</h2>

<div class="card mt-3">
    <div class="card-body">

        <?php if ($result->num_rows > 0): ?>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Skolēns</th>
                        <th>Datums</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["fname"]) ?></td>
                        <td><?= htmlspecialchars($row["date"]) ?></td>
                        <td>
                            <form action="update_request.php" method="post" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?= $row["id"] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button class="btn btn-success btn-sm">Apstiprināt</button>
                            </form>

                            <form action="update_request.php" method="post" style="display:inline;">
                                <input type="hidden" name="request_id" value="<?= $row["id"] ?>">
                                <input type="hidden" name="action" value="reject">
                                <button class="btn btn-danger btn-sm">Noraidīt</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>

                </tbody>
            </table>

        <?php else: ?>

            <div class="alert alert-info">
                Nav gaidošu pieprasījumu.
            </div>

        <?php endif; ?>

    </div>
</div>
