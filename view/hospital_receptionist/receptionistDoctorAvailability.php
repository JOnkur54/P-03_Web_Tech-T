<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['availability_data'])) { header("Location: ../../controllers/receptionistDoctorAvailabilityController.php"); exit(); }
$availability = $_SESSION['availability_data'];
$date         = isset($_SESSION['availability_date']) ? $_SESSION['availability_date'] : date('Y-m-d');
unset($_SESSION['availability_data'], $_SESSION['availability_date']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Availability</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Doctor Availability</h2>
        <form method="GET" action="../../controllers/receptionistDoctorAvailabilityController.php" novalidate style="margin-bottom:16px;">
            <input type="date" name="date" value="<?php echo htmlspecialchars($date); ?>" style="padding:8px;border:1px solid #ccc;border-radius:4px;">
            <input type="submit" value="Check" style="background:#0033a0;color:#fff;border:none;padding:8px 18px;border-radius:4px;cursor:pointer;margin-left:8px;">
        </form>
        <?php if (empty($availability)) { ?>
            <p>No doctors available on <?php echo date('l, d M Y', strtotime($date)); ?>.</p>
        <?php } else { ?>
            <table>
                <thead><tr><th>#</th><th>Doctor</th><th>Specialization</th><th>Start Time</th><th>End Time</th><th>Slot Duration</th><th>Booked</th></tr></thead>
                <tbody>
                <?php $i=1; foreach ($availability as $av) { ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($av['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($av['specialization']); ?></td>
                    <td><?php echo date('h:i A', strtotime($av['start_time'])); ?></td>
                    <td><?php echo date('h:i A', strtotime($av['end_time'])); ?></td>
                    <td><?php echo $av['slot_duration_minutes']; ?> min</td>
                    <td><?php echo $av['booked_count']; ?> appointments</td>
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