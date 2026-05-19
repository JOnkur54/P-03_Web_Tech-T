<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['summary_data'])) { header("Location: ../../controllers/receptionistDailySummaryController.php"); exit(); }
$summary = $_SESSION['summary_data'];
$date    = isset($_SESSION['summary_date']) ? $_SESSION['summary_date'] : date('Y-m-d');
unset($_SESSION['summary_data'], $_SESSION['summary_date']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Summary</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Daily Appointment Summary</h2>
        <form method="GET" action="../../controllers/receptionistDailySummaryController.php" novalidate style="margin-bottom:20px;">
            <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" style="padding:8px;border:1px solid #ccc;border-radius:4px;">
            <input type="submit" value="View" style="background:#0033a0;color:#fff;border:none;padding:8px 18px;border-radius:4px;cursor:pointer;margin-left:8px;">
        </form>
        <h3>Report for: <?php echo date('l, d F Y', strtotime($date)); ?></h3>
        <div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-top:16px;">
            <div class="stat-box s1"><div class="stat-value"><?php echo $summary['total_appointments']; ?></div><div class="stat-label">Total Appointments</div></div>
            <div class="stat-box s2"><div class="stat-value"><?php echo $summary['total_checkins']; ?></div><div class="stat-label">Check-ins</div></div>
            <div class="stat-box s3"><div class="stat-value"><?php echo $summary['total_completed']; ?></div><div class="stat-label">Completed</div></div>
            <div class="stat-box s4"><div class="stat-value"><?php echo $summary['total_cancelled']; ?></div><div class="stat-label">Cancelled</div></div>
            <div class="stat-box s5"><div class="stat-value"><?php echo $summary['total_noshows']; ?></div><div class="stat-label">No-Shows</div></div>
            <div class="stat-box s6"><div class="stat-value">&#2547; <?php echo number_format($summary['revenue'],2); ?></div><div class="stat-label">Revenue Collected</div></div>
        </div>
        <div style="margin-top:16px;">
            <button onclick="window.print()" class="btn btn-success">Print Report</button>
        </div>
    </div>
</div>
<?php include "../partials/receptionistRight.php"; ?>
</div>
<?php include "../partials/receptionistFooter.php"; ?>