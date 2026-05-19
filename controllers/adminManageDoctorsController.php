<?php
session_start();

require_once "../model/connect.php";
require_once "../model/adminDoctorModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../view/hospital_admin/adminLogin.php");
    exit();
}

$conn = connect();
$_SESSION['doctors']         = adminGetAllDoctors($conn);
$_SESSION['specializations'] = adminGetAllSpecializations($conn);
close($conn);


header("Location: ../view/hospital_admin/adminManageDoctors.php");
exit();
?>