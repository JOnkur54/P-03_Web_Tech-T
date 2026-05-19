<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['medical_notes'])) { header("Location: ../../controllers/patientMedicalHistoryShowController.php"); exit(); }
$notes   = $_SESSION['medical_notes'];
$errors  = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['medical_notes'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical History</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Medical History Notes</h2>
        <?php if ($success) { ?><div class="success"><?php echo htmlspecialchars($success); ?></div><?php } ?>
        <?php if (!empty($errors)) { ?><div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div><?php } ?>
        <form action="../../controllers/patientMedicalHistoryController.php" method="POST" onsubmit="return validate(this)" novalidate>
            <label>Add Medical Note:</label>
            <textarea name="note_text" id="note_text" rows="5" placeholder="Add a personal medical note, symptom or health observation..."></textarea>
            <span id="noteErr"></span>
            <input type="submit" value="Save Note">
        </form>
    </div>
    <div class="card">
        <h3>Your Medical Notes</h3>
        <?php if (empty($notes)) { ?>
            <p>No medical notes saved yet.</p>
        <?php } else { ?>
            <?php foreach ($notes as $note) { ?>
                <div class="note-card"><p><?php echo nl2br(htmlspecialchars($note)); ?></p></div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>
<script src="../js/patientMedicalHistory.js"></script>