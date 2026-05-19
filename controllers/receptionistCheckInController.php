<?php
session_start();
require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: ../view/hospital_receptionist/receptionistLogin.php"); exit(); }
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == "checkin") {
    $appointment_id = isset($_POST['appointment_id']) ? (int)$_POST['appointment_id'] : 0;
    $conn = connect();
    $status = receptionistCheckIn($conn, $appointment_id);
    close($conn);
    $_SESSION['success'] = $status ? "Patient checked in successfully." : "Check-in failed.";
    header("Location: ../controllers/receptionistCheckInController.php");
    exit();
}
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$conn = connect();
$_SESSION['checkin_results'] = $search != "" ? receptionistSearchTodayAppointments($conn, $search) : [];
$_SESSION['checkin_search']  = $search;
close($conn);
header("Location: ../view/hospital_receptionist/receptionistCheckIn.php");
exit();