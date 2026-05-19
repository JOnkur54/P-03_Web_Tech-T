<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['waiting_room'])) { header("Location: ../../controllers/receptionistWaitingRoomController.php"); exit(); }
$queue = $_SESSION['waiting_room'];
unset($_SESSION['waiting_room']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting Room</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Waiting Room — <?php echo date('d M Y'); ?></h2>
        <p style="color:#666;margin-bottom:16px;"><?php echo count($queue); ?> patient(s) currently waiting.</p>
        <a href="../../controllers/receptionistWaitingRoomController.php" style="background:#0033a0;color:#fff;padding:8px 16px;border-radius:4px;text-decoration:none;font-size:13px;margin-bottom:16px;display:inline-block;">Refresh</a>
        <?php if (empty($queue)) { ?>
            <p style="margin-top:16px;">No patients waiting right now.</p>
        <?php } else { ?>
            <table>
                <thead><tr><th>#</th><th>Appt Time</th><th>Patient</th><th>Phone</th><th>Doctor</th><th>Specialization</th></tr></thead>
                <tbody>
                <?php $i=1; foreach ($queue as $q) { ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo date('h:i A', strtotime($q['appointment_time'])); ?></td>
                    <td><?php echo htmlspecialchars($q['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($q['patient_phone']); ?></td>
                    <td><?php echo htmlspecialchars($q['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($q['specialization']); ?></td>
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