<?php
session_start();

if(!isset($_SESSION['patient_id'])){
    header("Location: login.php");
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">

<div class="card">

<h2>Welcome <?php echo htmlspecialchars($_SESSION['patient_name'] ?? 'Patient'); ?></h2>

<p>Patient Dashboard</p>

</div>

<div class="card">

<h3>Quick Actions</h3>

<ul>
<li><a href="bookAppointment.php" style="color:#0d6efd; text-decoration:none;">Book Appointment</a></li>
<li><a href="manageProfile.php" style="color:#0d6efd; text-decoration:none;">Manage Profile</a></li>
<li><a href="doctors.php" style="color:#0d6efd; text-decoration:none;">View Doctors</a></li>
<li><a href="billing.php" style="color:#0d6efd; text-decoration:none;">Check Billing</a></li>
</ul>

</div>
</div>
    

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>