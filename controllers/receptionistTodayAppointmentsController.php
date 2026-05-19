<?php
session_start();
require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: ../view/hospital_receptionist/receptionistLogin.php"); exit(); }
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : "";
$conn = connect();
$_SESSION['today_appointments'] = receptionistGetTodayAppointments($conn, $status_filter);
$_SESSION['today_filter']       = $status_filter;
close($conn);
header("Location: ../view/hospital_receptionist/receptionistTodayAppointments.php");
exit();