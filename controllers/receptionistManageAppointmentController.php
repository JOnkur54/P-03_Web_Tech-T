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
    $action         = isset($_POST['action'])         ? $_POST['action']                   : "";
    $appointment_id = isset($_POST['appointment_id']) ? (int)$_POST['appointment_id']      : 0;
    $conn = connect();
    if ($action == "cancel") {
        $status = receptionistCancelAppointment($conn, $appointment_id);
        $_SESSION['success'] = $status ? "Appointment cancelled." : "Cancellation failed.";
    }
    if ($action == "reschedule") {
        $new_date = isset($_POST['new_date']) ? trim($_POST['new_date']) : "";
        $new_time = isset($_POST['new_time']) ? trim($_POST['new_time']) : "";
        if ($new_date == "" || $new_time == "") { $_SESSION['errors'] = ["Please select a new date and time slot."]; }
        else {
            $status = receptionistRescheduleAppointment($conn, $appointment_id, $new_date, $new_time);
            $_SESSION['success'] = $status ? "Appointment rescheduled." : "Reschedule failed.";
        }
    }
    close($conn);
    header("Location: ../controllers/receptionistManageAppointmentController.php");
    exit();
}
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$conn = connect();
$_SESSION['manage_results'] = $search != "" ? receptionistSearchTodayAppointments($conn, $search) : [];
$_SESSION['manage_search']  = $search;
$_SESSION['manage_doctors'] = receptionistGetApprovedDoctors($conn);
close($conn);
header("Location: ../view/hospital_receptionist/receptionistManageAppointment.php");
exit();