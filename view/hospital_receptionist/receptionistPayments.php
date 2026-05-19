<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['pending_bills'])) { header("Location: ../../controllers/receptionistPaymentsController.php"); exit(); }
$bills   = $_SESSION['pending_bills'];
$errors  = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['pending_bills'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payments</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Process Payments</h2>
        <p id="msg"><?php echo $success ? htmlspecialchars($success) : ""; ?></p>
        <?php if (!empty($errors)) { ?>
            <div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div>
        <?php } ?>
        <?php if (empty($bills)) { ?>
            <p>No pending bills found.</p>
        <?php } else { ?>
            <table>
                <thead><tr><th>#</th><th>Date</th><th>Patient</th><th>Doctor</th><th>Amount (&#2547;)</th><th>Payment Method</th><th>Action</th></tr></thead>
                <tbody>
                <?php $i=1; foreach ($bills as $bill) { ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($bill['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($bill['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($bill['doctor_name']); ?></td>
                    <td><?php echo number_format($bill['amount'],2); ?></td>
                    <td>
                        <form action="../../controllers/receptionistPaymentsController.php" method="POST" novalidate>
                            <input type="hidden" name="bill_id" value="<?php echo $bill['bill_id']; ?>">
                            <select name="payment_method" style="padding:5px;border:1px solid #ccc;border-radius:4px;">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="mobile_banking">Mobile Banking</option>
                            </select>
                            <button type="submit" class="btn btn-success" style="margin-left:6px;">Mark Paid</button>
                        </form>
                    </td>
                    <td></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
<?php include "../partials/receptionistRight.php"; ?>
</div>
<?php include "../partials/receptionistFooter.php"; ?>