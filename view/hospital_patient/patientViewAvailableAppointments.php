<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: patientLogin.php");
    exit();
}

if (!isset($_SESSION['doctors'])) {
    header("Location: ../../controllers/patientViewAvailableAppointmentsShowController.php");
    exit();
}

$doctors          = $_SESSION['doctors'];
$slots            = isset($_SESSION['slots'])              ? $_SESSION['slots']              : [];
$doctor_id        = isset($_SESSION['selected_doctor_id']) ? $_SESSION['selected_doctor_id'] : 0;
$appointment_date = isset($_SESSION['appointment_date'])   ? $_SESSION['appointment_date']   : date('Y-m-d');
$errors           = isset($_SESSION['errors'])             ? $_SESSION['errors']             : [];
$success          = isset($_SESSION['success'])            ? $_SESSION['success']            : "";

unset($_SESSION['doctors'], $_SESSION['slots'], $_SESSION['selected_doctor_id'], $_SESSION['appointment_date'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Available Slots</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>

<?php include "../partials/patientHeader.php"; ?>

<div class="layout">

<?php include "../partials/patientLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Available Appointment Slots</h2>

        <?php if ($success) { ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php } ?>

        <?php if (!empty($errors)) { ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <div class="form-group" style="margin-bottom:14px;">
            <label for="doctorSelect">Select Doctor:</label>
            <select id="doctorSelect">
                <option value="">Choose a doctor</option>
                <?php foreach ($doctors as $doctor) { ?>
                    <option value="<?php echo $doctor['id']; ?>" <?php if ($doctor_id == $doctor['id']) { echo "selected"; } ?>>
                        <?php echo htmlspecialchars($doctor['doctor_name']); ?> — <?php echo htmlspecialchars($doctor['specialization']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label for="appointmentDate">Select Date:</label>
            <input type="date" id="appointmentDate" value="<?php echo htmlspecialchars($appointment_date); ?>">
        </div>

        <h3>Available Slots</h3>
        <div id="slotsContainer">
            <?php if (!empty($slots)) { ?>
                <div class="slot-buttons">
                    <?php foreach ($slots as $slot) { ?>
                        <span class="slot-btn"><?php echo htmlspecialchars($slot['label']); ?></span>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p style="font-size:13px;color:#666;">Select a doctor and date to see available slots.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php include "../partials/patientRight.php"; ?>

</div>

<?php include "../partials/patientFooter.php"; ?>

<script src="../js/patientViewSlots.js"></script>

</body>
</html>