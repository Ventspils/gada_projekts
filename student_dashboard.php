<?php
require "db.php";

$student_id = $_SESSION["id"];

$month = date("n");

if ($month >= 9 && $month <= 12) {
    $semester = "1";
} elseif ($month >= 1 && $month <= 5) {
    $semester = "2";
} else {
    $semester = null;
}

$stmt = $conn->prepare("
    SELECT * FROM vb_request 
    WHERE student_id = ? 
    AND semester = ?
    ORDER BY created_at DESC
    LIMIT 1
");

$stmt->bind_param("is", $student_id, $semester);
$stmt->execute();

$result = $stmt->get_result();
$request = $result->fetch_assoc();
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Skolēna panelis</h2>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Iziet</a>
    </div>

    <div class="card mt-3">
        <div class="card-body">

            <?php if ($request && in_array($request["status"], ["pending", "approved"])): ?>

                <?php if ($request["status"] === "pending"): ?>
                    <div class="alert alert-warning">
                        Gaida apstiprinājumu
                    </div>
                <?php elseif ($request["status"] === "approved"): ?>
                    <div class="alert alert-success">
                        Tev jau ir apstiprināta labbūtības diena uz <?= date("d.m.Y", strtotime($request["date"])) ?>.
                    </div>
                <?php endif; ?>

            <?php else: ?>

                <?php if ($request && $request["status"] === "rejected"): ?>
                    <div class="alert alert-danger">
                        Tavs pieteikums uz <?= date("d.m.Y", strtotime($request["date"])) ?> tika noraidīts.
                    </div>
                <?php elseif ($request && $request["status"] === "cancel"): ?>
                    <div class="alert alert-secondary">
                        Tu atcēli pieteikumu uz <?= date("d.m.Y", strtotime($request["date"])) ?>.
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        Tev nav aktīva pieteikuma.
                    </div>
                <?php endif; ?>

                <?php
                $available_dates = [];
                $workday_count = 0;
                $date = new DateTime();

                while (count($available_dates) < 5) {
                    $date->modify('+1 day');
                    $dayOfWeek = $date->format('N');

                    if ($dayOfWeek < 6) {
                        $workday_count++;

                        if ($workday_count >= 2) {
                            $available_dates[] = $date->format('Y-m-d');
                        }
                    }
                }
                ?>

                <form action="create_request.php" method="post">
                    <div class="mb-3">
                        <label class="form-label">Izvēlies datumu:</label>
                        <select name="selected_date" class="form-select" required>
                            <?php foreach ($available_dates as $d): ?>
                                <option value="<?= $d ?>">
                                    <?= date("d.m.Y", strtotime($d)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Pieteikt labbūtības dienu
                    </button>
                </form>

            <?php endif; ?>

            <?php if ($request && $request["status"] === "pending"): ?>
                <form action="update_request.php" method="post">
                    <input type="hidden" name="request_id" value="<?= $request["id"] ?>">
                    <input type="hidden" name="action" value="cancel">
                    <button class="btn btn-secondary mt-2">
                        Atcelt pieteikumu
                    </button>
                </form>
            <?php endif; ?>

        </div>
    </div>

    <?php
    $history = $conn->prepare("
        SELECT date, status 
        FROM vb_request
        WHERE student_id = ?
        ORDER BY created_at DESC
    ");
    $history->bind_param("i", $student_id);
    $history->execute();
    $history_result = $history->get_result();
    ?>

    <div class="card mt-3">
        <div class="card-body">
            <h5>Pieteikumu vēsture</h5>

            <?php if ($history_result->num_rows > 0): ?>

                <ul class="list-group">

                <?php while ($row = $history_result->fetch_assoc()): ?>
                    <li class="list-group-item d-flex justify-content-between">

                        <span><?= date("d.m.Y", strtotime($row["date"])) ?></span>

                        <?php if ($row["status"] === "approved"): ?>
                            <span class="badge bg-success">Apstiprināts</span>
                        <?php elseif ($row["status"] === "rejected"): ?>
                            <span class="badge bg-danger">Noraidīts</span>
                        <?php elseif ($row["status"] === "cancel"): ?>
                            <span class="badge bg-secondary">Atcelts</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Gaida</span>
                        <?php endif; ?>

                    </li>
                <?php endwhile; ?>

                </ul>

            <?php else: ?>

                <div class="alert alert-info">
                    Nav vēstures.
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>