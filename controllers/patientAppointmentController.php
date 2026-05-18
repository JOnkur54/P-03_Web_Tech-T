<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital appointment booking/login.php");
    exit();
}

$_SESSION['errors'] = [];
$_SESSION['success'] = "";

/*
|--------------------------------------------------------------------------
| Cancel Appointment
|--------------------------------------------------------------------------
*/
if (isset($_GET['action']) && $_GET['action'] == "cancel") {

    $appointment_id = (int)$_GET['id'];

    $conn = connect();
    $patient = getPatientByUserId($conn, $_SESSION['patient_id']);

    if (!$patient) {
        close($conn);
        $_SESSION['errors'] = ["Patient not found."];
        header("Location: ../view/hospital appointment booking/upcomingAppointments.php");
        exit();
    }

    $patient_id = $patient['id'];
    $status = cancelAppointment($conn, $appointment_id, $patient_id);
    close($conn);

    if ($status) {
        $_SESSION['success'] = "Appointment cancelled successfully.";
    } else {
        $_SESSION['errors'] = ["Failed to cancel appointment."];
    }

    header("Location: ../view/hospital appointment booking/upcomingAppointments.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Book Appointment
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == "book") {

    $doctor_id = (int)$_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $reason = $_POST['reason'];

    if ($doctor_id == 0 || $appointment_date == "" || $appointment_time == "") {
        $_SESSION['errors'] = ["Please fill in all required fields."];
        header("Location: ../view/hospital appointment booking/bookAppointment.php");
        exit();
    }

    $conn = connect();
    $patient = getPatientByUserId($conn, $_SESSION['patient_id']);

    if (!$patient) {
        close($conn);
        $_SESSION['errors'] = ["Patient profile not found."];
        header("Location: ../view/hospital appointment booking/bookAppointment.php");
        exit();
    }

    $data = [
        'patient_id' => $patient['id'],
        'doctor_id' => $doctor_id,
        'appointment_date' => $appointment_date,
        'appointment_time' => $appointment_time,
        'reason' => $reason
    ];

    $status = bookAppointment($conn, $data);
    close($conn);

    if ($status) {
        $_SESSION['success'] = "Appointment booked successfully.";
    } else {
        $_SESSION['errors'] = ["Failed to book appointment."];
    }

    header("Location: ../view/hospital appointment booking/bookAppointment.php");
    exit();
}

header("Location: ../view/hospital appointment booking/patientDashboard.php");
exit();