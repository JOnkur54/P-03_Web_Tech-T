<?php
session_start();
require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: ../view/hospital_receptionist/receptionistLogin.php"); exit(); }
$bill_id = isset($_GET['bill_id']) ? (int)$_GET['bill_id'] : 0;
if ($bill_id == 0) { header("Location: ../controllers/receptionistPaymentsController.php"); exit(); }
$conn = connect();
$_SESSION['receipt_bill'] = receptionistGetBillById($conn, $bill_id);
close($conn);
header("Location: ../view/hospital_receptionist/receptionistReceipt.php");
exit();