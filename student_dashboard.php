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
    AND (status = 'pending' OR status = 'approved')
");

$stmt->bind_param("is", $student_id, $semester);
$stmt->execute();

$result = $stmt->get_result();
$request = $result->fetch_assoc();
?>

<h2>Skolēna panelis</h2>

<div class="card mt-3">
    <div class="card-body">

        <?php if ($request): ?>

            <?php if ($request["status"] === "pending"): ?>
                <div class="alert alert-warning">
                    Tavs pieteikums gaida apstiprinājumu.
                </div>
            <?php elseif ($request["status"] === "approved"): ?>
                <div class="alert alert-success">
                    Tev jau ir apstiprināta labbūtības diena.
                </div>
            <?php endif; ?>

        <?php else: ?>

            <div class="alert alert-info">
                Tev nav aktīva pieteikuma.
            </div>

            <?php
            $available_dates = [];
            $workday_count = 0;
            $date = new DateTime();

            while (count($available_dates) < 5) {
                $date->modify('+1 day');
                $dayOfWeek = $date->format('N'); // 1 = Mon, 7 = Sun

                if ($dayOfWeek < 6) { // tikai P–Pk
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
                                <?= $d ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Pieteikt labbūtības dienu
                </button>
            </form>

        <?php endif; ?>

    </div>
</div>

