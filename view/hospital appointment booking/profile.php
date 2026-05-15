<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../../model/patientModel.php';
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">
    <div class="card">
        <h2>Patient Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($patient['name'] ?? ''); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email'] ?? ''); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient['phone'] ?? ''); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient['date_of_birth'] ?? ''); ?></p>
        <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($patient['blood_group'] ?? ''); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars(ucfirst($patient['gender'] ?? '')); ?></p>
        <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($patient['address'] ?? '')); ?></p>
        <p><strong>Emergency Contact:</strong> <?php echo htmlspecialchars($patient['emergency_contact_name'] ?? ''); ?> — <?php echo htmlspecialchars($patient['emergency_contact_phone'] ?? ''); ?></p>
        <p><a href="manageProfile.php" style="color:#0d6efd; text-decoration:none;">Edit profile</a></p>
    </div>
</div>

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>