<?php
session_start();
if (!isset($_SESSION['patient_id'])) { header("Location: patientLogin.php"); exit(); }
if (!isset($_SESSION['past_appointments'])) { header("Location: ../../controllers/patientAppointmentHistoryShowController.php"); exit(); }
$appointments = $_SESSION['past_appointments'];
unset($_SESSION['past_appointments']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History</title>
    <link rel="stylesheet" href="../css/patient.css">
</head>
<body>
<?php include "../partials/patientHeader.php"; ?>
<div class="layout">
<?php include "../partials/patientLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Appointment History</h2>
        <?php if (empty($appointments)) { ?>
            <p>No past appointments yet.</p>
        <?php } else { ?>
            <table>
                <thead><tr><th>Date</th><th>Time</th><th>Doctor</th><th>Specialization</th><th>Status</th><th>Notes</th></tr></thead>
                <tbody>
                <?php foreach ($appointments as $appt) { $s = $appt['status']; ?>
                <tr>
                    <td><?php echo htmlspecialchars($appt['appointment_date']); ?></td>
                    <td><?php echo date("h:i A", strtotime($appt['appointment_time'])); ?></td>
                    <td><?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                    <td><?php echo htmlspecialchars($appt['specialization']); ?></td>
                    <td><span class="badge-<?php echo $s; ?>"><?php echo ucfirst(str_replace('_',' ',$s)); ?></span></td>
                    <td>
                        <?php if ($s == "completed") { ?>
                            <a href="../../controllers/patientConsultationNotesShowController.php?appointment_id=<?php echo $appt['id']; ?>" class="btn btn-info" style="text-decoration:none;font-size:12px;padding:4px 10px;">View Notes</a>
                        <?php } else { ?>
                            <span style="color:#999;font-size:12px;">—</span>
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