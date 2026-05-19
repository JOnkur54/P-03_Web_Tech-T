<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital_patient/patientLogin.php");
    exit();
}

$_SESSION['errors']  = [];
$_SESSION['success'] = "";

/* ── Cancel Appointment ─────────────────────────────────────────── */
if (isset($_GET['action']) && $_GET['action'] == "cancel") {

    $appointment_id = (int)$_GET['id'];

    $conn    = connect();
    $patient = getPatientByUserId($conn, $_SESSION['patient_id']);

    if (!$patient) {
        close($conn);
        $_SESSION['errors'] = ["Patient not found."];
        header("Location: ../controllers/patientUpcomingAppointmentsController.php");
        exit();
    }

    $patient_id = $patient['id'];
    $status     = cancelAppointment($conn, $appointment_id, $patient_id);
    close($conn);

    if ($status) {
        $_SESSION['success'] = "Appointment cancelled successfully.";
    } else {
        $_SESSION['errors'] = ["Failed to cancel appointment."];
    }

    header("Location: ../controllers/patientUpcomingAppointmentsController.php");
    exit();
}

/* ── Book Appointment ───────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == "book") {

    $doctor_id        = (int)$_POST['doctor_id'];
    $appointment_date = isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : "";
    $appointment_time = isset($_POST['appointment_time']) ? trim($_POST['appointment_time']) : "";
    $reason           = isset($_POST['reason'])           ? trim($_POST['reason'])           : "";
    $booking_for      = isset($_POST['booking_for'])      ? trim($_POST['booking_for'])      : "self";

    if ($doctor_id == 0 || $appointment_date == "" || $appointment_time == "") {
        $_SESSION['errors'] = ["Please fill in all required fields."];
        header("Location: ../controllers/patientBookAppointmentShowController.php");
        exit();
    }

    $conn    = connect();
    $patient = getPatientByUserId($conn, $_SESSION['patient_id']);

    if (!$patient) {
        close($conn);
        $_SESSION['errors'] = ["Patient profile not found."];
        header("Location: ../controllers/patientBookAppointmentShowController.php");
        exit();
    }

    $patient_id = $patient['id'];

    if ($booking_for != "self" && strpos($booking_for, "dependent_") === 0) {
        $dep_id    = (int)str_replace("dependent_", "", $booking_for);
        $dependent = getDependentById($conn, $dep_id, $patient_id);
        if ($dependent) {
            $patient_id = isset($dependent['patient_id']) ? (int)$dependent['patient_id'] : $patient_id;
        }
    }

    $data = [
        'patient_id'       => $patient_id,
        'doctor_id'        => $doctor_id,
        'appointment_date' => $appointment_date,
        'appointment_time' => $appointment_time,
        'reason'           => $reason,
    ];

    $status = bookAppointment($conn, $data);
    close($conn);

    if ($status) {
        $_SESSION['success'] = "Appointment booked successfully.";
        header("Location: ../controllers/patientUpcomingAppointmentsController.php");
    } else {
        $_SESSION['errors'] = ["Failed to book appointment."];
        header("Location: ../controllers/patientBookAppointmentShowController.php");
    }
    exit();
}

header("Location: ../view/hospital_patient/patientDashboard.php");
exit();
?>