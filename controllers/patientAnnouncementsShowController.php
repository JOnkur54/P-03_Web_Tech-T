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
$announcements = getPatientAnnouncementsAll($conn);
close($conn);

$_SESSION['announcements'] = $announcements;

header("Location: ../view/hospital_patient/patientAnnouncements.php");
exit();
?>