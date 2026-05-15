<?php
session_start();
require_once '../model/patientModel.php';

if (!isset($_SESSION['patient_id'])) {
    header('Location: ../view/hospital appointment booking/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billId = isset($_POST['bill_id']) ? (int)$_POST['bill_id'] : 0;
    if ($billId > 0) {
        if (markBillingPaid($conn, $billId)) {
            $_SESSION['success'] = 'Payment marked as paid.';
        } else {
            $_SESSION['errors'][] = 'Unable to update payment status.';
        }
    }
}

header('Location: ../view/hospital appointment booking/billing.php');
exit();
?>