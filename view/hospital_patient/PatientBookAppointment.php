<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['doctors'])) { header("Location: ../../controllers/patientBookAppointmentShowController.php"); exit(); }
$doctors         = $_SESSION['doctors'];
$dependents      = isset($_SESSION['dependents'])         ? $_SESSION['dependents']         : [];
$selected_doctor = isset($_SESSION['selected_doctor_id']) ? $_SESSION['selected_doctor_id'] : 0;
$errors          = isset($_SESSION['errors'])             ? $_SESSION['errors']             : [];
$success         = isset($_SESSION['success'])            ? $_SESSION['success']            : "";
unset($_SESSION['doctors'], $_SESSION['dependents'], $_SESSION['selected_doctor_id'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Book Appointment</h2>
        <?php if ($success) { ?><div class="success"><?php echo htmlspecialchars($success); ?></div><?php } ?>
        <?php if (!empty($errors)) { ?><div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div><?php } ?>
        <form action="../../controllers/patientAppointmentController.php" method="POST" onsubmit="return validate(this)" novalidate>
            <input type="hidden" name="action" value="book">
            <label>Booking For:</label>
            <select name="booking_for">
                <option value="self">Myself</option>
                <?php foreach ($dependents as $dep) { ?>
                    <option value="dependent_<?php echo $dep['id']; ?>"><?php echo htmlspecialchars($dep['name']); ?> (<?php echo htmlspecialchars($dep['relationship']); ?>)</option>
                <?php } ?>
            </select>
            <label>Select Doctor:</label>
            <select name="doctor_id" id="doctor_id">
                <option value="">Choose a doctor</option>
                <?php foreach ($doctors as $doc) { ?>
                    <option value="<?php echo $doc['id']; ?>" <?php if ($doc['id'] == $selected_doctor) { echo "selected"; } ?>>
                        <?php echo htmlspecialchars($doc['doctor_name']); ?> — <?php echo htmlspecialchars($doc['specialization']); ?> (&#2547;<?php echo $doc['consultation_fee']; ?>)
                    </option>
                <?php } ?>
            </select>
            <span id="doctorErr"></span>
            <label>Appointment Date:</label>
            <input type="date" name="appointment_date" id="appointment_date" min="<?php echo date('Y-m-d'); ?>">
            <span id="dateErr"></span>
            <label>Available Time Slots:</label>
            <div id="slotsContainer" class="slot-buttons">
                <p style="font-size:13px;color:#666;">Select a doctor and date to view slots.</p>
            </div>
            <input type="hidden" name="appointment_time" id="appointment_time">
            <span id="timeErr"></span>
            <label>Reason for Visit:</label>
            <textarea name="reason" rows="3" placeholder="Describe your symptoms or reason for visit"></textarea>
            <span id="reasonErr"></span>
            <input type="submit" value="Book Appointment">
        </form>
    </div>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>
<script src="../js/patientBookAppointment.js"></script>