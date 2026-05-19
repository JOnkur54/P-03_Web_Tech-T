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

$appointments = [];
if ($patient && isset($patient['id'])) {
    $appointments = getUpcomingAppointments($conn, $patient['id']);
}

close($conn);

$_SESSION['appointments'] = $appointments;
$_SESSION['success']      = isset($_SESSION['success']) ? $_SESSION['success'] : "";
$_SESSION['errors']       = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];

header("Location: ../view/hospital_patient/upcomingAppointments.php");
exit();
?>