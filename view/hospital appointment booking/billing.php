<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}
require_once '../../model/patientModel.php';
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$billing = getBillingHistory($conn, $patient['id'] ?? 0);
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['errors'], $_SESSION['success']);
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/left.php'; ?>

<div class="main">
    <div class="card">
        <h2>Billing History</h2>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="error"><ul><?php foreach ($errors as $error): ?><li><?php echo htmlspecialchars($error); ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <?php if (empty($billing)): ?>
            <p>No billing records found.</p>
        <?php else: ?>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background:#f1f3f6;"><th>Date</th><th>Doctor</th><th>Amount</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                <?php foreach ($billing as $invoice): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($invoice['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['doctor_name']); ?></td>
                        <td>&#2547; <?php echo htmlspecialchars($invoice['amount']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($invoice['payment_status'])); ?></td>
                        <td>
                            <?php if ($invoice['payment_status'] === 'pending'): ?>
                                <form action="../../controllers/patientBillingController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="bill_id" value="<?php echo (int)$invoice['id']; ?>">
                                    <input type="submit" value="Mark Paid" style="background:#198754; color:#fff; padding:6px 10px; border:none; border-radius:4px; cursor:pointer;">
                                </form>
                            <?php else: ?>
                                Paid
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include '../partials/right.php'; ?>
<?php include '../partials/footer.php'; ?>