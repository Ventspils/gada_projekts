<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "db.php";
$sort = $_GET["sort"] ?? "date_asc";

switch ($sort) {
    case "date_desc":
        $order = "r.date DESC";
        break;
    case "name_asc":
        $order = "u.fname ASC";
        break;
    case "name_desc":
        $order = "u.fname DESC";
        break;
    default:
        $order = "r.date ASC";
}
$stmt = $conn->prepare("
    SELECT r.id, r.student_id, r.`date`, r.created_at,
           u.fname
    FROM vb_request r
    JOIN vb_users u ON r.student_id = u.id_users
    WHERE r.status = 'pending'
    ORDER BY $order
");

$stmt->execute();
$result = $stmt->get_result();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Skolotāja panelis</h2>
    <a href="archive.php" class="btn btn-dark">
        Arhīvs
    </a>
</div>

<div class="card mt-3">
    <div class="card-body">

        <?php if ($result->num_rows > 0): ?>

            <form method="get" class="mb-3">
                <select name="sort" onchange="this.form.submit()" class="form-select w-auto">
                    <option value="date_asc" <?= $sort == "date_asc" ? "selected" : "" ?>>
                        Tuvākie datumi
                    </option>
                    <option value="date_desc" <?= $sort == "date_desc" ? "selected" : "" ?>>
                        Tālākie datumi
                    </option>
                    <option value="name_asc" <?= $sort == "name_asc" ? "selected" : "" ?>>
                        Vārds A-Z
                    </option>
                    <option value="name_desc" <?= $sort == "name_desc" ? "selected" : "" ?>>
                        Vārds Z-A
                    </option>
                </select>
            </form>

            <div class="table-responsive">
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
        <?php else: ?>

            <div class="alert alert-info">
                Nav gaidošu pieprasījumu.
            </div>

        <?php endif; ?>

    </div>
</div>