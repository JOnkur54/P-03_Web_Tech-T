<?php
session_start();
require_once "../model/connect.php";
require_once "../model/adminReportsModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: ../view/hospital_admin/adminLogin.php"); exit(); }

$filters = [
    'doctor_id' => isset($_GET['doctor_id']) ? trim($_GET['doctor_id']) : "",
    'date'      => isset($_GET['date'])      ? trim($_GET['date'])      : "",
    'status'    => isset($_GET['status'])    ? trim($_GET['status'])    : "",
    'booked_by' => isset($_GET['booked_by']) ? trim($_GET['booked_by']) : ""
];

$conn = connect();
$_SESSION['all_appointments'] = adminGetAllAppointments($conn, $filters);
$_SESSION['doctors_list']     = adminGetDoctorsSimple($conn);
$_SESSION['appt_filters']     = $filters;
close($conn);

header("Location: ../view/hospital_admin/adminAllAppointments.php");
exit();
?>