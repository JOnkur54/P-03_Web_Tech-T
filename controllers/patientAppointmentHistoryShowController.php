<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital appointment booking/login.php");
    exit();
}

$selected_doctor_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;

$conn = connect();
$doctors = getApprovedDoctors($conn);
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);

$dependents = [];
if ($patient && isset($patient['id'])) {
    $dependents = getPatientDependents($conn, $patient['id']);
}

close($conn);

$_SESSION['doctors'] = $doctors;
$_SESSION['dependents'] = $dependents;
$_SESSION['selected_doctor_id'] = $selected_doctor_id;

header("Location: ../view/hospital appointment booking/bookAppointment.php");
exit();