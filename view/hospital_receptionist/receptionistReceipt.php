<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['receipt_bill'])) { header("Location: ../../controllers/receptionistPaymentsController.php"); exit(); }
$bill = $_SESSION['receipt_bill'];
unset($_SESSION['receipt_bill']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card" id="receiptCard">
        <div style="text-align:center;margin-bottom:24px;">
            <h2 style="color:#0033a0;font-size:24px;">MediBook Hospital</h2>
            <p style="color:#666;">Payment Receipt</p>
            <hr style="margin:12px 0;">
        </div>
        <table>
            <tr><th style="width:40%;">Receipt No.</th><td>#<?php echo $bill['id']; ?></td></tr>
            <tr><th>Patient Name</th><td><?php echo htmlspecialchars($bill['patient_name']); ?></td></tr>
            <tr><th>Patient Phone</th><td><?php echo htmlspecialchars($bill['patient_phone']); ?></td></tr>
            <tr><th>Doctor</th><td><?php echo htmlspecialchars($bill['doctor_name']); ?></td></tr>
            <tr><th>Specialization</th><td><?php echo htmlspecialchars($bill['specialization']); ?></td></tr>
            <tr><th>Appointment Date</th><td><?php echo htmlspecialchars($bill['appointment_date']); ?></td></tr>
            <tr><th>Appointment Time</th><td><?php echo date('h:i A', strtotime($bill['appointment_time'])); ?></td></tr>
            <tr><th>Amount</th><td><strong>&#2547; <?php echo number_format($bill['amount'],2); ?></strong></td></tr>
            <tr><th>Payment Method</th><td><?php echo ucfirst(str_replace('_',' ',$bill['payment_method'])); ?></td></tr>
            <tr><th>Paid At</th><td><?php echo $bill['paid_at'] ? date('d M Y h:i A', strtotime($bill['paid_at'])) : 'N/A'; ?></td></tr>
            <tr><th>Status</th><td><span class="badge-paid">PAID</span></td></tr>
        </table>
        <div style="text-align:center;margin-top:20px;color:#666;font-size:13px;">
            <p>Thank you for choosing MediBook Hospital.</p>
        </div>
    </div>
    <div style="margin-top:16px;">
        <button onclick="window.print()" class="btn btn-success">Print Receipt</button>
        <a href="../../controllers/receptionistPaymentsController.php" class="btn btn-secondary" style="text-decoration:none;margin-left:8px;">Back to Payments</a>
    </div>
</div>
<?php include "../partials/receptionistRight.php"; ?>
</div>
<?php include "../partials/receptionistFooter.php"; ?>