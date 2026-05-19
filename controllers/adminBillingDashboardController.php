<?php
session_start();
require_once "../model/connect.php";
require_once "../model/adminReportsModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: ../view/hospital_admin/adminLogin.php"); exit(); }
$conn = connect();
$_SESSION['billing_summary'] = adminGetBillingSummary($conn);
$_SESSION['all_bills']       = adminGetAllBills($conn);
close($conn);
header("Location: ../view/hospital_admin/adminBillingDashboard.php");
exit();
?>