<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['doctors'])) {
    header("Location: ../../controllers/patientBookAppointmentShowController.php");
    exit();
}

$doctors = $_SESSION['doctors'];
$dependents = isset($_SESSION['dependents']) ? $_SESSION['dependents'] : [];
$selected_doctor_id = isset($_SESSION['selected_doctor_id']) ? $_SESSION['selected_doctor_id'] : 0;
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['doctors'], $_SESSION['dependents'], $_SESSION['selected_doctor_id'], $_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../css/bookAppointment.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2>Book Appointment</h2>

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

        <form action="../../controllers/patientAppointmentController.php" method="POST" onsubmit="return validate(this)" novalidate>
            
            <input type="hidden" name="action" value="book">

            <label for="booking_for"><b>Booking For:</b></label>
            <select name="booking_for" id="booking_for">
                <option value="self">Myself</option>
                <?php foreach ($dependents as $dep) { ?>
                    <option value="dependent_<?php echo $dep['id']; ?>">
                        <?php echo $dep['name']; ?> (<?php echo $dep['relationship']; ?>)
                    </option>
                <?php } ?>
            </select>

            <label for="doctor_id"><b>Select Doctor:</b></label>
            <select name="doctor_id" id="doctor_id">
                <option value="">Choose a doctor</option>
                <?php foreach ($doctors as $doctor) { ?>
                    <option value="<?php echo $doctor['id']; ?>" <?php if ($doctor['id'] == $selected_doctor_id) echo "selected"; ?>>
                        <?php echo $doctor['doctor_name']; ?> - <?php echo $doctor['specialization']; ?> (Fee: ৳<?php echo $doctor['consultation_fee']; ?>)
                    </option>
                <?php } ?>
            </select>
            <span id="doctorErr"></span>

            <label for="appointment_date"><b>Appointment Date:</b></label>
            <input type="date" name="appointment_date" id="appointment_date" min="<?php echo date('Y-m-d'); ?>">
            <span id="dateErr"></span>

            <label for="appointment_time"><b>Available Time Slots:</b></label>
            <div id="slotsContainer" class="slots-container">
                <p>Please select a doctor and date to view available slots.</p>
            </div>
            <input type="hidden" name="appointment_time" id="appointment_time">
            <span id="timeErr"></span>

            <label for="reason"><b>Reason for Visit:</b></label>
            <textarea name="reason" id="reason" rows="3" placeholder="Describe your symptoms or reason for consultation"></textarea>
            <span id="reasonErr"></span>

            <input type="submit" value="Book Appointment">

        </form>

    </div>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

<script src="../js/bookAppointment.js"></script>

</body>
</html>