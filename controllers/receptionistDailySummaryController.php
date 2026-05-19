<?php
session_start();
require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: ../view/hospital_receptionist/receptionistLogin.php"); exit(); }
$date = isset($_GET['date']) ? trim($_GET['date']) : date('Y-m-d');
$conn = connect();
$_SESSION['summary_data'] = receptionistGetDailySummary($conn, $date);
$_SESSION['summary_date'] = $date;
close($conn);
header("Location: ../view/hospital_receptionist/receptionistDailySummary.php");
exit();
?>