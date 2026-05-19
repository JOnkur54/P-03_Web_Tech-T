<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['rec_doctors'])) { header("Location: ../../controllers/receptionistBookAppointmentController.php"); exit(); }
$doctors  = $_SESSION['rec_doctors'];
$patients = isset($_SESSION['rec_patients']) ? $_SESSION['rec_patients'] : [];
$errors   = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success  = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['rec_doctors'], $_SESSION['rec_patients'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Book Walk-In Appointment</h2>
        <p id="msg"><?php echo $success ? htmlspecialchars($success) : ""; ?></p>
        <?php if (!empty($errors)) { ?>
            <div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div>
        <?php } ?>
        <form action="../../controllers/receptionistBookAppointmentController.php" method="POST" novalidate>
            <label for="patient_id">Select Patient:</label>
            <select name="patient_id" id="patient_id">
                <option value="">-- Select Patient --</option>
                <?php foreach ($patients as $pat) { ?>
                    <option value="<?php echo $pat['patient_id']; ?>"><?php echo htmlspecialchars($pat['name']); ?> (<?php echo htmlspecialchars($pat['phone']); ?>)</option>
                <?php } ?>
            </select>
            <span id="patientErr"></span>
            <label for="doctor_id">Select Doctor:</label>
            <select name="doctor_id" id="doctor_id">
                <option value="">-- Select Doctor --</option>
                <?php foreach ($doctors as $doc) { ?>
                    <option value="<?php echo $doc['id']; ?>"><?php echo htmlspecialchars($doc['doctor_name']); ?> — <?php echo htmlspecialchars($doc['specialization']); ?> (&#2547;<?php echo $doc['consultation_fee']; ?>)</option>
                <?php } ?>
            </select>
            <span id="doctorErr"></span>
            <label for="appointment_date">Appointment Date:</label>
            <input type="date" name="appointment_date" id="appointment_date" min="<?php echo date('Y-m-d'); ?>">
            <span id="dateErr"></span>
            <label>Available Time Slots:</label>
            <div id="slotsContainer"><p>Select a doctor and date to see available slots.</p></div>
            <input type="hidden" name="appointment_time" id="appointment_time">
            <span id="timeErr"></span>
            <label for="reason">Reason for Visit:</label>
            <textarea name="reason" id="reason" rows="3" placeholder="Describe symptoms or reason"></textarea>
            <input type="submit" value="Book Appointment">
        </form>
    </div>
</div>
<?php include "../partials/receptionistRight.php"; ?>
</div>
<?php include "../partials/receptionistFooter.php"; ?>
<script src="../js/receptionistBookAppointment.js"></script>