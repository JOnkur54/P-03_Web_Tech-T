<?php
session_start();
require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: ../view/hospital_receptionist/receptionistLogin.php"); exit(); }
$conn = connect();
$_SESSION['dash_today']    = receptionistGetTodayAppointments($conn);
$_SESSION['dash_waiting']  = receptionistGetWaitingRoom($conn);
$_SESSION['dash_summary']  = receptionistGetDailySummary($conn, date('Y-m-d'));
close($conn);
header("Location: ../view/hospital_receptionist/receptionistDashboard.php");
exit();