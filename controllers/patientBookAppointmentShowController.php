<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital appointment booking/login.php");
    exit();
}

$conn = connect();
$doctors = getApprovedDoctors($conn);
close($conn);

$_SESSION['doctors'] = $doctors;
$_SESSION['selected_doctor_id'] = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;

header("Location: ../view/hospital appointment booking/bookAppointment.php");
exit();