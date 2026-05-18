<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital appointment booking/login.php");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$conn = connect();
$doctors = getApprovedDoctors($conn, $search);
close($conn);

$_SESSION['doctors'] = $doctors;
$_SESSION['search'] = $search;

header("Location: ../view/hospital appointment booking/doctors.php");
exit();