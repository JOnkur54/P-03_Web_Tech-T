<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: adminLogin.php"); exit(); }
if (!isset($_SESSION['billing_summary'])) { header("Location: ../../controllers/adminBillingDashboardController.php"); exit(); }

$summary = $_SESSION['billing_summary'];
$bills   = isset($_SESSION['all_bills']) ? $_SESSION['all_bills'] : [];
unset($_SESSION['billing_summary'], $_SESSION['all_bills']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Dashboard</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>
<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>Billing Dashboard</h2>

        <div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
            <div style="flex:1;background:#d1fae5;padding:18px;border-radius:8px;border-left:4px solid #198754;">
                <div style="font-size:12px;color:#065f46;text-transform:uppercase;letter-spacing:1px;">Total Paid</div>
                <div style="font-size:28px;font-weight:700;color:#065f46;">&#2547; <?php echo number_format($summary['total_paid'], 2); ?></div>
            </div>
            <div style="flex:1;background:#fff3cd;padding:18px;border-radius:8px;border-left:4px solid #ffc107;">
                <div style="font-size:12px;color:#856404;text-transform:uppercase;letter-spacing:1px;">Total Pending</div>
                <div style="font-size:28px;font-weight:700;color:#856404;">&#2547; <?php echo number_format($summary['total_pending'], 2); ?></div>
            </div>
            <div style="flex:1;background:#fee2e2;padding:18px;border-radius:8px;border-left:4px solid #dc3545;">
                <div style="font-size:12px;color:#991b1b;text-transform:uppercase;letter-spacing:1px;">Overdue Bills</div>
                <div style="font-size:28px;font-weight:700;color:#991b1b;"><?php echo $summary['overdue_count']; ?></div>
            </div>
        </div>

        <button onclick="window.print()" style="background:#198754;color:#fff;border:none;padding:8px 16px;border-radius:4px;cursor:pointer;margin-bottom:12px;">Print Report</button>

        <?php if (empty($bills)) { ?>
            <p>No billing records found.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Appt Date</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Amount (&#2547;)</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Paid At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($bills as $bill) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($bill['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($bill['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($bill['doctor_name']); ?></td>
                        <td><?php echo number_format($bill['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($bill['payment_method']); ?></td>
                        <td>
                            <?php if ($bill['payment_status'] == "paid") { ?>
                                <span style="color:#198754;font-weight:bold;">Paid</span>
                            <?php } else { ?>
                                <span style="color:#dc3545;font-weight:bold;">Pending</span>
                            <?php } ?>
                        </td>
                        <td><?php echo $bill['paid_at'] ? date('d M Y', strtotime($bill['paid_at'])) : "—"; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>

<?php include "../partials/adminRight.php"; ?>

</div>

<?php include "../partials/adminFooter.php"; ?>
</body>
</html>