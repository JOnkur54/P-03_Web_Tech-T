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
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
close($conn);

$_SESSION['patient_profile'] = $patient;

header("Location: ../view/hospital_patient/patientProfile.php");
exit();