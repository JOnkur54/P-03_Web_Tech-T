<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital_patient/patientLogin.php");
    exit();
}

$search         = isset($_GET['search'])         ? trim($_GET['search'])         : "";
$specialization = isset($_GET['specialization']) ? trim($_GET['specialization']) : "";
$fee_min        = isset($_GET['fee_min'])        ? (float)$_GET['fee_min']       : 0;
$fee_max        = isset($_GET['fee_max'])        ? (float)$_GET['fee_max']       : 0;

$conn = connect();

$doctors         = getApprovedDoctors($conn, $search, $specialization, $fee_min, $fee_max);
$specializations = getAllSpecializationsList($conn);

close($conn);

$_SESSION['doctors']         = $doctors;
$_SESSION['search']          = $search;
$_SESSION['specialization']  = $specialization;
$_SESSION['specializations'] = $specializations;

header("Location: ../view/hospital_patient/patientDoctors.php");
exit();