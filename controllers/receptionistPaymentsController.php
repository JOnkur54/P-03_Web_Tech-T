<?php
session_start();
require_once "../model/connect.php";
require_once "../model/receptionistModel.php";
require_once "../model/close.php";
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: ../view/hospital_receptionist/receptionistLogin.php"); exit(); }
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $bill_id        = isset($_POST['bill_id'])        ? (int)$_POST['bill_id']                  : 0;
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method'])           : "cash";
    if ($bill_id > 0) {
        $conn   = connect();
        $status = receptionistMarkBillPaid($conn, $bill_id, $payment_method);
        close($conn);
        if ($status) {
            header("Location: ../controllers/receptionistReceiptController.php?bill_id=" . $bill_id);
            exit();
        }
        $_SESSION['errors'] = ["Payment processing failed."];
    } else {
        $_SESSION['errors'] = ["Invalid bill."];
    }
    header("Location: ../controllers/receptionistPaymentsController.php");
    exit();
}
$conn = connect();
$_SESSION['pending_bills'] = receptionistGetPendingBills($conn);
close($conn);
header("Location: ../view/hospital_receptionist/receptionistPayments.php");
exit();