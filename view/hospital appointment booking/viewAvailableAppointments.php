<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['doctors'])) {
    header("Location: ../../controllers/patientViewAvailableAppointmentsShowController.php");
    exit();
}

$doctors = $_SESSION['doctors'];
$slots = isset($_SESSION['slots']) ? $_SESSION['slots'] : [];
$doctor_id = isset($_SESSION['selected_doctor_id']) ? $_SESSION['selected_doctor_id'] : 0;
$appointment_date = isset($_SESSION['appointment_date']) ? $_SESSION['appointment_date'] : date('Y-m-d');
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['doctors'], $_SESSION['slots'], $_SESSION['selected_doctor_id'], $_SESSION['appointment_date'], $_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Appointments</title>
    <link rel="stylesheet" href="../css/viewAvailableAppointments.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2>Available Appointment Slots</h2>

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

        <div class="form-group">
            <label for="doctorSelect">Select Doctor:</label>
            <select id="doctorSelect">
                <option value="">Choose Doctor</option>
                <?php foreach ($doctors as $doctor) { ?>
                    <option value="<?php echo $doctor['id']; ?>" <?php if ($doctor_id == $doctor['id']) { echo "selected"; } ?>>
                        <?php echo $doctor['doctor_name'] . " - " . $doctor['specialization']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="appointmentDate">Select Date:</label>
            <input type="date" id="appointmentDate" value="<?php echo $appointment_date; ?>">
        </div>

        <div class="slot-section">
            <h3>Available Slots</h3>
            <div id="slotsContainer">
                <?php if (!empty($slots)) { ?>
                    <ul class="slot-list">
                        <?php foreach ($slots as $slot) { ?>
                            <li class="slot-item"><?php echo $slot['label']; ?></li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <p>No slots available.</p>
                <?php } ?>
            </div>
        </div>

    </div>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

<script src="../js/viewAvailableAppointments.js"></script>

</body>
</html>