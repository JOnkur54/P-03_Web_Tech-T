<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['consultation_notes'])) {
    $aid = isset($_GET['appointment_id']) ? (int)$_GET['appointment_id'] : 0;
    header("Location: ../../controllers/patientConsultationNotesShowController.php?appointment_id=" . $aid);
    exit();
}
$notes = $_SESSION['consultation_notes'];
unset($_SESSION['consultation_notes']);
if (!$notes) { header("Location: patientAppointmentHistory.php"); exit(); }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Notes</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Consultation Notes</h2>
        <div class="appointment-info">
            <p><strong>Doctor:</strong> <?php echo htmlspecialchars($notes['doctor_name']); ?></p>
            <p><strong>Date:</strong> <?php echo date("d M Y", strtotime($notes['appointment_date'])); ?></p>
            <p><strong>Time:</strong> <?php echo date("h:i A", strtotime($notes['appointment_time'])); ?></p>
        </div>
        <div class="form-section">
            <h3>Doctor's Notes</h3>
            <?php if (!empty($notes['consultation_notes'])) { ?>
                <div class="notes-content"><?php echo nl2br(htmlspecialchars($notes['consultation_notes'])); ?></div>
            <?php } else { ?>
                <p style="color:#999;">No consultation notes available.</p>
            <?php } ?>
        </div>
        <div class="form-section">
            <h3>Prescription</h3>
            <?php if (!empty($notes['prescription'])) { ?>
                <div class="prescription-content"><?php echo nl2br(htmlspecialchars($notes['prescription'])); ?></div>
            <?php } else { ?>
                <p style="color:#999;">No prescription available.</p>
            <?php } ?>
        </div>
        <div class="action-buttons">
            <a href="patientAppointmentHistory.php" class="back-btn">Back to History</a>
            <button onclick="window.print()" class="print-btn">Print</button>
        </div>
    </div>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>