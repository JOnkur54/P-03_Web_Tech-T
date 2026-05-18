<?php
session_start();

require_once "../model/connect.php";
require_once "../model/adminStaffPatientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: ../view/hospital_admin/adminLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../controllers/adminManagePatientsController.php");
    exit();
}

$action  = isset($_POST['action'])  ? $_POST['action']  : "";
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

$conn = connect();

if ($action == "deactivate") {
    adminTogglePatientStatus($conn, $user_id, 0);
    $_SESSION['success'] = "Patient account deactivated.";
}

if ($action == "activate") {
    adminTogglePatientStatus($conn, $user_id, 1);
    $_SESSION['success'] = "Patient account activated.";
}

close($conn);

header("Location: ../controllers/adminManagePatientsController.php");
exit();