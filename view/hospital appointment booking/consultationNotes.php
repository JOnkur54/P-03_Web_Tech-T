<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['consultation_notes'])) {
    $appointment_id = isset($_GET['appointment_id']) ? (int)$_GET['appointment_id'] : 0;
    header("Location: ../../controllers/patientConsultationNotesShowController.php?appointment_id=" . $appointment_id);
    exit();
}

$notes = $_SESSION['consultation_notes'];

unset($_SESSION['consultation_notes']);

if (!$notes) {
    header("Location: appointmentHistory.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Notes</title>
    <link rel="stylesheet" href="../css/consultationNotes.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2>Consultation Notes</h2>

        <div class="appointment-info">
            <p><b>Doctor:</b> <?php echo $notes['doctor_name']; ?></p>
            <p><b>Date:</b> <?php echo date("M d, Y", strtotime($notes['appointment_date'])); ?></p>
            <p><b>Time:</b> <?php echo date("h:i A", strtotime($notes['appointment_time'])); ?></p>
        </div>

        <div class="notes-section">
            <h3>Doctor's Notes</h3>
            <?php if (!empty($notes['consultation_notes'])) { ?>
                <div class="notes-content">
                    <?php echo nl2br($notes['consultation_notes']); ?>
                </div>
            <?php } else { ?>
                <p class="no-notes">No consultation notes available for this appointment.</p>
            <?php } ?>
        </div>

        <div class="prescription-section">
            <h3>Prescription</h3>
            <?php if (!empty($notes['prescription'])) { ?>
                <div class="prescription-content">
                    <?php echo nl2br($notes['prescription']); ?>
                </div>
            <?php } else { ?>
                <p class="no-prescription">No prescription available for this appointment.</p>
            <?php } ?>
        </div>

        <div class="action-buttons">
            <a href="appointmentHistory.php" class="back-btn">Back to History</a>
            <button onclick="window.print()" class="print-btn">Print</button>
        </div>

    </div>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

</body>
</html>