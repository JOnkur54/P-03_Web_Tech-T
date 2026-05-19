<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div style="margin-bottom:20px;">
        <h2>Dashboard</h2>
        <p style="color:#666;font-size:13px;">Welcome back, <strong><?php echo htmlspecialchars(isset($_SESSION['patient_name']) ? $_SESSION['patient_name'] : 'Patient'); ?></strong> &mdash; <?php echo date('l, d F Y'); ?></p>
    </div>
    <div class="card">
        <h3>Quick Actions</h3>
        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:10px;">
            <a href="../../controllers/patientBookAppointmentShowController.php" class="btn btn-primary">Book Appointment</a>
            <a href="../../controllers/patientUpcomingAppointmentsController.php" class="btn btn-info">My Appointments</a>
            <a href="../../controllers/patientDoctorsShowController.php" class="btn btn-success">Browse Doctors</a>
            <a href="../../controllers/patientBillingShowController.php" class="btn btn-warning">Check Billing</a>
            <a href="../../controllers/patientManageProfileShowController.php" class="btn btn-primary">Manage Profile</a>
            <a href="../../controllers/patientAnnouncementsShowController.php" class="btn btn-success">Announcements</a>
        </div>
    </div>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>