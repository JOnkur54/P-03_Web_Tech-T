<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../view/hospital_admin/adminLogin.php");
    exit();
}

require_once "../model/connect.php";
require_once "../model/adminDashboardModel.php";
require_once "../model/close.php";

$conn = connect();

$_SESSION['dash_today_count']     = adminGetTodayAppointmentCount($conn);
$_SESSION['dash_total_patients']  = adminGetTotalPatients($conn);
$_SESSION['dash_total_doctors']   = adminGetTotalActiveDoctors($conn);
$_SESSION['dash_pending_bills']   = adminGetTotalPendingBillings($conn);
$_SESSION['dash_today_appts']     = adminGetTodayAppointments($conn);
$_SESSION['dash_recent_patients'] = adminGetRecentPatients($conn);
$_SESSION['dash_pending_doctors'] = adminGetPendingDoctorCount($conn);


close($conn);

header("Location: ../view/hospital_admin/adminDashboard.php");
exit();