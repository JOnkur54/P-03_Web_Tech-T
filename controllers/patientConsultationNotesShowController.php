<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital_patient/patientLogin.php");
    exit();
}

$appointment_id = isset($_GET['appointment_id']) ? (int)$_GET['appointment_id'] : 0;

if ($appointment_id == 0) {
    header("Location: ../view/hospital_patient/patientAppointmentHistory.php");
    exit();
}

$conn = connect();
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);

$notes = null;
if ($patient && isset($patient['id'])) {
    $notes = getConsultationNotes($conn, $appointment_id, $patient['id']);
}

close($conn);

$_SESSION['consultation_notes'] = $notes;

header("Location: ../view/hospital_patient/patientConsultationNotes.php");
exit();