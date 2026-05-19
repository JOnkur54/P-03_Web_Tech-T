<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['dash_today'])) { header("Location: ../../controllers/receptionistDashboardController.php"); exit(); }
$today    = $_SESSION['dash_today'];
$waiting  = $_SESSION['dash_waiting'];
$summary  = $_SESSION['dash_summary'];
unset($_SESSION['dash_today'], $_SESSION['dash_waiting'], $_SESSION['dash_summary']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Dashboard</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div style="margin-bottom:20px;">
        <h2>Dashboard</h2>
        <p style="color:#666;font-size:13px;">Welcome, <strong><?php echo htmlspecialchars(isset($_SESSION['name']) ? $_SESSION['name'] : ''); ?></strong> &mdash; <?php echo date('l, d F Y'); ?></p>
    </div>
    <div class="stat-grid">
        <div class="stat-box s1"><div class="stat-value"><?php echo $summary['total_appointments']; ?></div><div class="stat-label">Total Appointments</div></div>
        <div class="stat-box s2"><div class="stat-value"><?php echo $summary['total_checkins']; ?></div><div class="stat-label">Checked In</div></div>
        <div class="stat-box s3"><div class="stat-value"><?php echo $summary['total_completed']; ?></div><div class="stat-label">Completed</div></div>
        <div class="stat-box s4"><div class="stat-value"><?php echo $summary['total_cancelled']; ?></div><div class="stat-label">Cancelled</div></div>
        <div class="stat-box s5"><div class="stat-value"><?php echo count($waiting); ?></div><div class="stat-label">Waiting Now</div></div>
        <div class="stat-box s6"><div class="stat-value">&#2547; <?php echo number_format($summary['revenue'], 2); ?></div><div class="stat-label">Revenue Today</div></div>
    </div>
    <div class="card">
        <h2>Today's Schedule (<?php echo date('d M Y'); ?>)</h2>
        <?php if (empty($today)) { ?>
            <p>No appointments today.</p>
        <?php } else { ?>
            <table>
                <thead><tr><th>Time</th><th>Patient</th><th>Doctor</th><th>Specialization</th><th>Booked By</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ($today as $appt) { $s = $appt['status']; ?>
                <tr>
                    <td><?php echo date('h:i A', strtotime($appt['appointment_time'])); ?></td>
                    <td><?php echo htmlspecialchars($appt['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($appt['specialization']); ?></td>
                    <td><?php echo ucfirst($appt['booked_by']); ?></td>
                    <td><span class="badge-<?php echo $s; ?>"><?php echo ucfirst(str_replace('_',' ',$s)); ?></span></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <?php if (!empty($waiting)) { ?>
    <div class="card">
        <h2>Waiting Room (<?php echo count($waiting); ?>)</h2>
        <table>
            <thead><tr><th>Time</th><th>Patient</th><th>Phone</th><th>Doctor</th></tr></thead>
            <tbody>
            <?php foreach ($waiting as $w) { ?>
            <tr>
                <td><?php echo date('h:i A', strtotime($w['appointment_time'])); ?></td>
                <td><?php echo htmlspecialchars($w['patient_name']); ?></td>
                <td><?php echo htmlspecialchars($w['patient_phone']); ?></td>
                <td><?php echo htmlspecialchars($w['doctor_name']); ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>
<?php include "../partials/receptionistRight.php"; ?>
</div>
<?php include "../partials/receptionistFooter.php"; ?>