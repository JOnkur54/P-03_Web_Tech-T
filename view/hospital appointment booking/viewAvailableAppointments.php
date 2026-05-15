<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../../model/connect.php";
require_once "../../model/patientModel.php";

$doctor_id = 0;
$appointment_date = date('Y-m-d');
$specializationFilter = 0;

if (isset($_GET['doctor_id'])) {
    $doctor_id = (int)$_GET['doctor_id'];
}

if (isset($_GET['date'])) {
    $appointment_date = $_GET['date'];
}

if (isset($_GET['specialization'])) {
    $specializationFilter = (int)$_GET['specialization'];
}

$errors = [];
$success = "";

if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

/*
|--------------------------------------------------------------------------
| Get Doctors
|--------------------------------------------------------------------------
*/

if ($specializationFilter > 0) {

    $doctors = getApprovedDoctors(
        $conn,
        '',
        (string)$specializationFilter
    );

} else {

    $doctors = getApprovedDoctors($conn);
}

/*
|--------------------------------------------------------------------------
| Get Available Slots
|--------------------------------------------------------------------------
*/

$slots = [];

if ($doctor_id > 0 && !empty($appointment_date)) {

    $slots = getAvailableSlots(
        $conn,
        $doctor_id,
        $appointment_date
    );
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Available Appointments</title>

    <style>
.card {
    background-color: #ffffff;
    padding: 71px;
    margin : 20px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.main{
    width: 60%;
    padding: 20px;
    margin : 10px 300px;
}

</style>

</head>

<body>

<?php include "../partials/header.php"; ?>

<div class="container">

    <?php include "../partials/left.php"; ?>

    <div class="main">

        <div class="card">

            <h2>Available Appointment Slots</h2>

            <?php if (!empty($success)) { ?>

                <div class="success">

                    <?php echo htmlspecialchars($success); ?>

                </div>

            <?php } ?>

            <?php if (!empty($errors)) { ?>

                <div class="error">

                    <ul>

                        <?php foreach ($errors as $error) { ?>

                            <li>

                                <?php echo htmlspecialchars($error); ?>

                            </li>

                        <?php } ?>

                    </ul>

                </div>

            <?php } ?>

            <div class="form-group">

                <label>Select Doctor</label>

                <select
                    id="doctorSelect"
                    onchange="loadSlots()">

                    <option value="">

                        Choose Doctor

                    </option>

                    <?php foreach ($doctors as $doctor) { ?>

                        <option
                            value="<?php echo (int)$doctor['id']; ?>"
                            <?php if ($doctor_id == $doctor['id']) echo "selected"; ?>>

                            <?php
                            echo htmlspecialchars(
                                $doctor['doctor_name']
                                . " - " .
                                $doctor['specialization']
                            );
                            ?>

                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="form-group">

                <label>Select Date</label>

                <input
                    type="date"
                    id="appointmentDate"
                    value="<?php echo htmlspecialchars($appointment_date); ?>"
                    onchange="loadSlots()">

            </div>

            <div class="slot-section">

                <h3>Available Slots</h3>

                <div id="slotsContainer">

                    <?php if (!empty($slots)) { ?>

                        <ul class="slot-list">

                            <?php foreach ($slots as $slot) { ?>

                                <li class="slot-item">

                                    <?php echo htmlspecialchars($slot['label']); ?>

                                </li>

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

</div>

<?php include "../partials/footer.php"; ?>

<script>

function loadSlots() {

    let doctorId =
        document.getElementById("doctorSelect").value;

    let appointmentDate =
        document.getElementById("appointmentDate").value;

    let container =
        document.getElementById("slotsContainer");

    if (
        doctorId === "" ||
        appointmentDate === ""
    ) {

        container.innerHTML =
            "<p>Please select doctor and date.</p>";

        return;
    }

    container.innerHTML =
        "<p>Loading slots...</p>";

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {

        if (
            this.readyState == 4 &&
            this.status == 200
        ) {

            let data =
                JSON.parse(this.responseText);

            if (
                data.slots &&
                data.slots.length > 0
            ) {

                let output =
                    '<ul class="slot-list">';

                for (
                    let i = 0;
                    i < data.slots.length;
                    i++
                ) {

                    output +=
                        '<li class="slot-item">'
                        + data.slots[i].label +
                        '</li>';
                }

                output += '</ul>';

                container.innerHTML = output;

            } else {

                container.innerHTML =
                    "<p>No slots available.</p>";
            }
        }
    };

    xhttp.open(
        "GET",
        "../../controllers/patientDoctorController.php?action=getSlots&doctor_id="
        + doctorId +
        "&date=" + appointmentDate,
        true
    );

    xhttp.send();
}

window.onload = function () {

    let doctorId =
        document.getElementById("doctorSelect").value;

    let appointmentDate =
        document.getElementById("appointmentDate").value;

    if (
        doctorId !== "" &&
        appointmentDate !== ""
    ) {

        loadSlots();
    }
};

</script>

</body>

</html>