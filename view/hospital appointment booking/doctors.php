<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../../model/patientModel.php';
$search = trim($_GET['search'] ?? '');
$doctors = getApprovedDoctors($conn, $search);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">
    <div class="card">
        <h2>Browse Doctors</h2>
        <form method="GET" action="doctors.php" style="margin-bottom:20px;">
            <input type="text" name="search" placeholder="Search by name, speciality or keyword" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search">
        </form>
    </div>

    <?php if (empty($doctors)): ?>
        <div class="card"><p>No doctors matched your search.</p></div>
    <?php else: ?>
        <?php foreach ($doctors as $doctor): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($doctor['doctor_name']); ?></h3>
                <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
                <p><strong>Experience:</strong> <?php echo htmlspecialchars($doctor['experience_years']); ?> years</p>
                <p><strong>Consultation Fee:</strong> &#2547; <?php echo htmlspecialchars($doctor['consultation_fee']); ?></p>
                <p><?php echo htmlspecialchars($doctor['bio']); ?></p>
                <a href="doctorDetails.php?doctor_id=<?php echo (int)$doctor['id']; ?>" style="color:#0d6efd; text-decoration:none;">View Profile</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>