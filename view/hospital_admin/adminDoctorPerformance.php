<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: adminLogin.php"); exit(); }
if (!isset($_SESSION['performance_data'])) { header("Location: ../../controllers/adminDoctorPerformanceController.php"); exit(); }

$performance = $_SESSION['performance_data'];
unset($_SESSION['performance_data']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Performance</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>
<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Doctor Performance Report</h2>

        <button onclick="window.print()" style="background:#198754;color:#fff;border:none;padding:8px 16px;border-radius:4px;cursor:pointer;margin-bottom:12px;">Print Report</button>

        <?php if (empty($performance)) { ?>
            <p>No performance data available.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Completed</th>
                        <th>No-Shows</th>
                        <th>Total</th>
                        <th>No-Show Rate</th>
                        <th>Avg Rating</th>
                        <th>Reviews</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($performance as $row) {
                        $noShowRate = $row['total'] > 0 ? round(($row['no_shows'] / $row['total']) * 100, 1) : 0;
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                        <td><?php echo $row['completed']; ?></td>
                        <td><?php echo $row['no_shows']; ?></td>
                        <td><?php echo $row['total']; ?></td>
                        <td><?php echo $noShowRate; ?>%</td>
                        <td><?php echo $row['avg_rating'] ? $row['avg_rating'] . ' ★' : 'N/A'; ?></td>
                        <td><?php echo $row['review_count']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>

<?php include "../partials/adminRight.php"; ?>

</div>

<?php include "../partials/adminFooter.php"; ?>
</body>
</html>