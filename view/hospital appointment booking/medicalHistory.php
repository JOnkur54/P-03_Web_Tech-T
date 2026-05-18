<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['medical_notes'])) {
    header("Location: ../../controllers/patientMedicalHistoryShowController.php");
    exit();
}

$notes = $_SESSION['medical_notes'];
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['medical_notes'], $_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical History</title>
    <link rel="stylesheet" href="../css/medicalHistory.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2>Medical History</h2>

        <?php if (!empty($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>

        <?php if (!empty($errors)) { ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <form action="../../controllers/patientMedicalHistoryController.php" method="POST" onsubmit="return validate(this)" novalidate>
            <label for="note_text">Add Medical Note:</label>
            <textarea name="note_text" id="note_text" placeholder="Add a personal medical note or symptom..." rows="5"></textarea>
            <span id="noteErr"></span>
            <input type="submit" value="Save Note">
        </form>

    </div>

    <div class="card">
        <h3>Personal Medical Notes</h3>
        <?php if (empty($notes)) { ?>
            <p>No medical notes saved yet.</p>
        <?php } else { ?>
            <?php foreach ($notes as $note) { ?>
                <div class="note-card">
                    <p><?php echo nl2br($note); ?></p>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

<script src="../js/medicalHistory.js"></script>

</body>
</html>