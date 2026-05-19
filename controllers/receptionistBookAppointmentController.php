<?php
session_start();
require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: ../view/hospital_receptionist/receptionistLogin.php"); exit(); }
if (isset($_GET['action']) && $_GET['action'] == "getSlots") {
    header('Content-Type: application/json');
    $doctor_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
    $date      = isset($_GET['date'])      ? trim($_GET['date'])      : "";
    if ($doctor_id == 0 || $date == "") { echo json_encode(['slots' => []]); exit(); }
    $conn  = connect();
    $slots = receptionistGetAvailableSlots($conn, $doctor_id, $date);
    close($conn);
    echo json_encode(['slots' => $slots]);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = [];
    $patient_id = isset($_POST['patient_id'])       ? (int)$_POST['patient_id']                : 0;
    $doctor_id  = isset($_POST['doctor_id'])        ? (int)$_POST['doctor_id']                 : 0;
    $date       = isset($_POST['appointment_date']) ? trim($_POST['appointment_date'])          : "";
    $time       = isset($_POST['appointment_time']) ? trim($_POST['appointment_time'])          : "";
    $reason     = isset($_POST['reason'])           ? trim($_POST['reason'])                    : "";
    if ($patient_id == 0) { $errors[] = "Please select a patient."; }
    if ($doctor_id  == 0) { $errors[] = "Please select a doctor."; }
    if ($date == "")      { $errors[] = "Please select a date."; }
    if ($time == "")      { $errors[] = "Please select a time slot."; }
    if (!empty($errors))  { $_SESSION['errors'] = $errors; }
    else {
        $conn   = connect();
        $status = receptionistBookWalkIn($conn, ['patient_id' => $patient_id, 'doctor_id' => $doctor_id, 'appointment_date' => $date, 'appointment_time' => $time, 'reason' => $reason]);
        close($conn);
        $_SESSION['success'] = $status ? "Appointment booked successfully." : "Booking failed.";
    }
    header("Location: ../controllers/receptionistBookAppointmentController.php");
    exit();
}
$conn = connect();
$_SESSION['rec_doctors']  = receptionistGetApprovedDoctors($conn);
$_SESSION['rec_patients'] = receptionistGetAllPatients($conn);
close($conn);
header("Location: ../view/hospital_receptionist/receptionistBookAppointment.php");
exit();