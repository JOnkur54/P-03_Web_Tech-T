<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
include '../model/ModelDoctorConsultation.php';

$patient_id = $_GET['patient_id'] ?? 0;
$doctor_id = $_SESSION['doctor_id'];

if (!$patient_id) {
    header("Location: ViewDocDashboard.php");
    exit;
}

$consultationModel = new ModelDoctorConsultation($conn);
$history = $consultationModel->getPatientHistory($doctor_id, $patient_id);

$patient_name = "Patient";
if (!empty($history)) {
    $patient_name = htmlspecialchars($history[0]['patient_name'] ?? 'Patient');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient History - MediBook</title>
    <link rel="stylesheet" href="css/doctor.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">Medi<span>Book</span></div>
        <ul class="sidebar-nav">
            <li><a href="ViewDocDashboard.php">Dashboard</a></li>
            <li><a href="ViewDocAppointments.php">Appointments</a></li>
            <li><a href="ViewDocAvailability.php">Availability</a></li>
            <li><a href="ViewDocProfile.php">My Profile</a></li>
            <li><a href="ViewDocReviews.php">Reviews</a></li>
            <li><a href="ViewDocBilling.php">Earnings</a></li>
        </ul>
        <a href="../controllers/ContDocLogin.php?logout=true" class="logout-btn">Log Out</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Patient History: <?php echo $patient_name; ?></h1>
            <a href="ViewDocDashboard.php" class="btn btn-secondary" style="background-color: var(--secondary); color: var(--primary); text-decoration: none; padding: 10px 15px; border-radius: 8px; font-size: 14px; font-weight: 500;">Back to Dashboard</a>
        </div>

        <?php if (empty($history)): ?>
            <div class="card" style="background-color: white; padding: 20px; border-radius: 12px; box-shadow: var(--shadow); text-align: center; color: var(--gray);">
                No past consultations found for this patient.
            </div>
        <?php else: ?>
            <?php foreach ($history as $note): ?>
                <div class="history-card">
                    <h3>Visit Date: <?php echo date('M d, Y', strtotime($note['appointment_date'])); ?></h3>
                    <p><strong>Symptoms:</strong> <?php echo htmlspecialchars($note['symptoms']); ?></p>
                    <p><strong>Diagnosis:</strong> <?php echo htmlspecialchars($note['diagnosis']); ?></p>
                    <p><strong>Prescription:</strong> <?php echo htmlspecialchars($note['prescription']); ?></p>
                    <?php if ($note['follow_up_date']): ?>
                        <p><strong>Follow-up Date:</strong> <?php echo date('M d, Y', strtotime($note['follow_up_date'])); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
