<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital_patient/patientLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../view/hospital_patient/patientBilling.php");
    exit();
}

$bill_id = isset($_POST['bill_id']) ? (int)$_POST['bill_id'] : 0;

if ($bill_id == 0) {
    $_SESSION['errors'] = ["Invalid bill ID."];
} else {
    $conn = connect();
    $status = markBillingPaid($conn, $bill_id);
    close($conn);

    if ($status) {
        $_SESSION['success'] = "Bill marked as paid successfully.";
    } else {
        $_SESSION['errors'] = ["Unable to update billing status."];
    }
}

$conn = connect();
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$billing = [];
if ($patient && isset($patient['id'])) {
    $billing = getBillingHistory($conn, $patient['id']);
}
close($conn);

$_SESSION['billing_history'] = $billing;

header("Location: ../view/hospital_patient/patientBilling.php");
exit();