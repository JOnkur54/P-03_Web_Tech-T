<?php
session_start();

require_once "../model/connect.php";
require_once "../model/adminStaffPatientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../view/hospital_admin/adminLogin.php");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$conn = connect();
$_SESSION['patients'] = adminGetAllPatients($conn, $search);
$_SESSION['patient_search'] = $search;
close($conn);

header("Location: ../view/hospital_admin/adminManagePatients.php");
exit();