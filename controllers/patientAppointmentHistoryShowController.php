<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital_patient/patientLogin.php");
    exit();
}

$conn    = connect();
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);

$past_appointments = [];
if ($patient && isset($patient['id'])) {
    $past_appointments = getPastAppointments($conn, $patient['id']);
}

close($conn);

$_SESSION['past_appointments'] = $past_appointments;

header("Location: ../view/hospital_patient/patientAppointmentHistory.php");
exit();
?>