<?php
session_start();
require_once "../model/connect.php";
require_once "../model/adminReportsModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: ../view/hospital_admin/adminLogin.php"); exit(); }
if ($_SERVER['REQUEST_METHOD'] != "POST") { header("Location: ../controllers/adminComplaintsController.php"); exit(); }

$complaint_id = isset($_POST['complaint_id']) ? (int)$_POST['complaint_id'] : 0;
$response     = isset($_POST['response'])     ? trim($_POST['response'])     : "";

if ($complaint_id == 0 || $response == "") {
    $_SESSION['errors'] = ["Complaint ID and response are required."];
    header("Location: ../controllers/adminComplaintsController.php");
    exit();
}

$conn   = connect();
$status = adminResolveComplaint($conn, $complaint_id, $response);
close($conn);

$_SESSION['success'] = $status ? "Complaint marked as resolved." : "Action failed.";
header("Location: ../controllers/adminComplaintsController.php");
exit();