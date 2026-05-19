<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital_patient/patientLogin.php");
    exit();
}

$conn = connect();

$doctors = getApprovedDoctors($conn);

$patient    = getPatientByUserId($conn, $_SESSION['patient_id']);
$patient_id = isset($patient['id']) ? $patient['id'] : null;

$dependents = [];
if ($patient_id) {
    $dependents = getPatientDependents($conn, $patient_id);
}

close($conn);

$_SESSION['doctors']            = $doctors;
$_SESSION['dependents']         = $dependents;
$_SESSION['selected_doctor_id'] = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;

header("Location: ../view/hospital_patient/patientBookAppointment.php");
exit();