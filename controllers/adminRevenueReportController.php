<?php
// adminRevenueReportController.php
session_start();
require_once "../model/connect.php";
require_once "../model/adminReportsModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: ../view/hospital_admin/adminLogin.php"); exit(); }
$period = isset($_GET['period']) ? trim($_GET['period']) : "month";
$conn = connect();
$_SESSION['revenue_data']   = adminGetRevenueReport($conn, $period);
$_SESSION['revenue_period'] = $period;
close($conn);
header("Location: ../view/hospital_admin/adminRevenueReport.php");
exit();
?>