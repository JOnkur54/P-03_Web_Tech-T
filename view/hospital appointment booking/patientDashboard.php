<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="../css/patientDashboard.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">

    <div class="card">
        <h2>Welcome <?php echo isset($_SESSION['patient_name']) ? $_SESSION['patient_name'] : "Patient"; ?></h2>
        <p>Patient Dashboard</p>
    </div>

    <div class="card">
        <h3>Quick Actions</h3>
        <ul>
            <li><a href="../../controllers/patientBookAppointmentShowController.php"> Book Appointment</a></li>
            <li><a href="../../controllers/patientManageProfileShowController.php">Manage Profile</a></li>
            <li><a href="../../controllers/patientDoctorsShowController.php">View Doctors</a></li>
            <li><a href="../../controllers/patientBillingShowController.php">Check Billing</a></li>
        </ul>
    </div>

</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

</body>
</html>