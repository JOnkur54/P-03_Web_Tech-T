<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: adminLogin.php"); exit(); }
if (!isset($_SESSION['revenue_data'])) { header("Location: ../../controllers/adminRevenueReportController.php"); exit(); }

$revenue = $_SESSION['revenue_data'];
$period  = isset($_SESSION['revenue_period']) ? $_SESSION['revenue_period'] : "month";
unset($_SESSION['revenue_data'], $_SESSION['revenue_period']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Report</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>
<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Hospital Revenue Report</h2>

        <form method="GET" action="../../controllers/adminRevenueReportController.php" style="margin-bottom:16px;">
            <select name="period" style="padding:8px;border:1px solid #ccc;border-radius:4px;">
                <option value="day"   <?php if ($period == "day")   { echo "selected"; } ?>>Daily</option>
                <option value="week"  <?php if ($period == "week")  { echo "selected"; } ?>>Weekly</option>
                <option value="month" <?php if ($period == "month") { echo "selected"; } ?>>Monthly</option>
            </select>
            <input type="submit" value="View" style="background:#0033a0;color:#fff;border:none;padding:8px 18px;border-radius:4px;cursor:pointer;margin-left:8px;">
        </form>

        <button onclick="window.print()" style="background:#198754;color:#fff;border:none;padding:8px 16px;border-radius:4px;cursor:pointer;margin-bottom:12px;">Print Report</button>

        <?php if (empty($revenue)) { ?>
            <p>No revenue data found.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Period</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Paid Bills</th>
                        <th>Total Revenue (&#2547;)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($revenue as $row) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['period_label']); ?></td>
                        <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                        <td><?php echo $row['total_paid']; ?></td>
                        <td><strong>&#2547; <?php echo number_format($row['total_revenue'], 2); ?></strong></td>
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