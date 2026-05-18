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
$appointment_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$specializationFilter = isset($_GET['specialization']) ? (int)$_GET['specialization'] : 0;

$conn = connect();

if ($specializationFilter > 0) {
    $doctors = getApprovedDoctors($conn, '', (string)$specializationFilter);
} else {
    $doctors = getApprovedDoctors($conn);
}

$slots = [];
if ($doctor_id > 0 && !empty($appointment_date)) {
    $slots = getAvailableSlots($conn, $doctor_id, $appointment_date);
}

close($conn);

$_SESSION['doctors'] = $doctors;
$_SESSION['slots'] = $slots;
$_SESSION['selected_doctor_id'] = $doctor_id;
$_SESSION['appointment_date'] = $appointment_date;

header("Location: ../view/hospital appointment booking/viewAvailableAppointments.php");
exit();