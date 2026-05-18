<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['billing_history'])) {
    header("Location: ../../controllers/patientBillingShowController.php");
    exit();
}

$billing = $_SESSION['billing_history'];
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['billing_history'], $_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing History</title>
    <link rel="stylesheet" href="../css/billing.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2>Billing History</h2>

        <?php if (!empty($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>

        <?php if (!empty($errors)) { ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <?php if (empty($billing)) { ?>
            <p>No billing records found.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($billing as $invoice) { ?>
                        <tr>
                            <td><?php echo $invoice['appointment_date']; ?></td>
                            <td><?php echo $invoice['doctor_name']; ?></td>
                            <td>&#2547; <?php echo $invoice['amount']; ?></td>
                            <td><?php echo ucfirst($invoice['payment_status']); ?></td>
                            <td>
                                <?php if ($invoice['payment_status'] == "pending") { ?>
                                    <form action="../../controllers/patientBillingController.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="bill_id" value="<?php echo $invoice['id']; ?>">
                                        <input type="submit" value="Mark Paid" class="pay-btn">
                                    </form>
                                <?php } else { ?>
                                    <span class="paid-badge">Paid</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

    </div>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

</body>
</html>