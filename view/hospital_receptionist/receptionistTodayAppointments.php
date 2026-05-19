<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['today_appointments'])) { header("Location: ../../controllers/receptionistTodayAppointmentsController.php"); exit(); }
$appointments = $_SESSION['today_appointments'];
$filter       = isset($_SESSION['today_filter']) ? $_SESSION['today_filter'] : "";
unset($_SESSION['today_appointments'], $_SESSION['today_filter']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Appointments</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Today's Appointments — <?php echo date('d M Y'); ?></h2>
        <form method="GET" action="../../controllers/receptionistTodayAppointmentsController.php" style="margin-bottom:16px;">
            <select name="status" style="padding:8px;border:1px solid #ccc;border-radius:4px;margin-right:8px;">
                <option value="">All Statuses</option>
                <?php foreach (['pending','confirmed','checked_in','completed','cancelled','no_show'] as $st) { ?>
                    <option value="<?php echo $st; ?>" <?php if ($filter == $st) { echo "selected"; } ?>>
                        <?php echo ucfirst(str_replace('_',' ',$st)); ?>
                    </option>
                <?php } ?>
            </select>
            <input type="submit" value="Filter" style="background:#0033a0;color:#fff;border:none;padding:8px 16px;border-radius:4px;cursor:pointer;">
            <a href="../../controllers/receptionistTodayAppointmentsController.php" style="margin-left:10px;color:#0033a0;">Reset</a>
        </form>
        <?php if (empty($appointments)) { ?>
            <p>No appointments found.</p>
        <?php } else { ?>
            <table>
                <thead><tr><th>#</th><th>Time</th><th>Patient</th><th>Phone</th><th>Doctor</th><th>Specialization</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                <?php $i=1; foreach ($appointments as $appt) { $s = $appt['status']; ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo date('h:i A', strtotime($appt['appointment_time'])); ?></td>
                    <td><?php echo htmlspecialchars($appt['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($appt['patient_phone']); ?></td>
                    <td><?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($appt['specialization']); ?></td>
                    <td><span class="badge-<?php echo $s; ?>"><?php echo ucfirst(str_replace('_',' ',$s)); ?></span></td>
                    <td>
                        <?php if ($s == "pending" || $s == "confirmed") { ?>
                            <a href="../../controllers/receptionistCheckInController.php?search=<?php echo $appt['id']; ?>" class="btn btn-primary" style="text-decoration:none;padding:5px 10px;font-size:12px;">Check In</a>
                        <?php } ?>
                    </td>
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