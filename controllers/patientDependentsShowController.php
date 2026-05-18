<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital appointment booking/login.php");
    exit();
}

$conn = connect();
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);

$dependents = [];
if ($patient && isset($patient['id'])) {
    $dependents = getPatientDependents($conn, $patient['id']);
}

close($conn);

$_SESSION['dependents'] = $dependents;

header("Location: ../view/hospital appointment booking/dependents.php");
exit();