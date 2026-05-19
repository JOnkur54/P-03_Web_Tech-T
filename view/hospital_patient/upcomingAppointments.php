<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: ../../controllers/patientUpcomingAppointmentsController.php"); exit(); }
$appointments = isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];
$errors       = isset($_SESSION['errors'])       ? $_SESSION['errors']       : [];
$success      = isset($_SESSION['success'])      ? $_SESSION['success']      : "";
unset($_SESSION['appointments'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Appointments</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Upcoming Appointments</h2>
        <?php if ($success) { ?><div class="success"><?php echo htmlspecialchars($success); ?></div><?php } ?>
        <?php if (!empty($errors)) { ?><div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div><?php } ?>
        <?php if (empty($appointments)) { ?>
            <p>No upcoming appointments scheduled.</p>
        <?php } else { ?>
            <table>
                <thead><tr><th>Date</th><th>Time</th><th>Doctor</th><th>Specialization</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach ($appointments as $appt) { $s = $appt['status']; ?>
                <tr>
                    <td><?php echo htmlspecialchars($appt['appointment_date']); ?></td>
                    <td><?php echo date("h:i A", strtotime($appt['appointment_time'])); ?></td>
                    <td><?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($appt['specialization']); ?></td>
                    <td><span class="badge-<?php echo $s; ?>"><?php echo ucfirst(str_replace('_',' ',$s)); ?></span></td>
                    <td>
                        <?php if ($s != "cancelled" && $s != "completed" && $s != "checked_in") { ?>
                            <a href="../../controllers/patientAppointmentController.php?action=cancel&id=<?php echo $appt['id']; ?>" class="cancel-btn" onclick="return confirm('Cancel this appointment?');">Cancel</a>
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