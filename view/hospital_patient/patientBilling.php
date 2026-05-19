<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['billing_history'])) { header("Location: ../../controllers/patientBillingShowController.php"); exit(); }
$billing = $_SESSION['billing_history'];
$errors  = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['billing_history'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing History</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Billing History</h2>
        <?php if ($success) { ?><div class="success"><?php echo htmlspecialchars($success); ?></div><?php } ?>
        <?php if (!empty($errors)) { ?><div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div><?php } ?>
        <?php if (empty($billing)) { ?>
            <p>No billing records found.</p>
        <?php } else { ?>
            <table>
                <thead><tr><th>Date</th><th>Doctor</th><th>Amount (&#2547;)</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach ($billing as $inv) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($inv['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($inv['doctor_name']); ?></td>
                    <td><?php echo number_format($inv['amount'], 2); ?></td>
                    <td>
                        <?php if ($inv['payment_status'] == "paid") { ?>
                            <span class="badge-paid">Paid</span>
                        <?php } else { ?>
                            <span class="badge-pending">Pending</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($inv['payment_status'] == "pending") { ?>
                            <form action="../../controllers/patientBillingController.php" method="POST" style="display:inline;">
                                <input type="hidden" name="bill_id" value="<?php echo $inv['id']; ?>">
                                <button type="submit" class="pay-btn">Submit Payment</button>
                            </form>
                        <?php } else { ?>
                            <span class="paid-badge">&#10003; Paid</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
<?php include "../partials/patientRight.php"; ?>
</div>
<?php include "../partials/patientFooter.php"; ?>