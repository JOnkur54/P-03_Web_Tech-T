<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital appointment booking/login.php");
    exit();
}

$doctor_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;

if ($doctor_id == 0) {
    header("Location: ../view/hospital appointment booking/doctors.php");
    exit();
}

$conn = connect();
$doctor = getDoctorById($conn, $doctor_id);
$availability = [];

if ($doctor) {
    $availability = getDoctorAvailability($conn, $doctor_id);
}

close($conn);

$_SESSION['doctor_details'] = $doctor;
$_SESSION['doctor_availability'] = $availability;

header("Location: ../view/hospital appointment booking/doctorDetails.php");
exit();