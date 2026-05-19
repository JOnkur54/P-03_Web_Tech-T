<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: adminLogin.php"); exit(); }
if (!isset($_SESSION['volume_data'])) { header("Location: ../../controllers/adminVolumeReportController.php"); exit(); }

$data = $_SESSION['volume_data'];
unset($_SESSION['volume_data']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volume Report</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>
<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">

    <button onclick="window.print()" style="background:#198754;color:#fff;border:none;padding:8px 16px;border-radius:4px;cursor:pointer;margin-bottom:12px;">Print Report</button>

    <div class="card">
        <h2>Busiest Doctors</h2>
        <?php if (empty($data['doctors'])) { ?>
            <p>No data available.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr><th>#</th><th>Doctor</th><th>Specialization</th><th>Total Appointments</th></tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($data['doctors'] as $row) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                        <td><?php echo $row['total']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

    <div class="card">
        <h2>Most In-Demand Specializations</h2>
        <?php if (empty($data['specializations'])) { ?>
            <p>No data available.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr><th>#</th><th>Specialization</th><th>Total Appointments</th></tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($data['specializations'] as $row) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                        <td><?php echo $row['total']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

    <div class="card">
        <h2>Peak Appointment Days</h2>
        <?php if (empty($data['peak_days'])) { ?>
            <p>No data available.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr><th>#</th><th>Day</th><th>Total Appointments</th></tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($data['peak_days'] as $row) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row['day_name']); ?></td>
                        <td><?php echo $row['total']; ?></td>
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