<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['manage_results'])) { header("Location: ../../controllers/receptionistManageAppointmentController.php"); exit(); }
$results = $_SESSION['manage_results'];
$search  = isset($_SESSION['manage_search'])  ? $_SESSION['manage_search']  : "";
$doctors = isset($_SESSION['manage_doctors']) ? $_SESSION['manage_doctors'] : [];
$errors  = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['manage_results'], $_SESSION['manage_search'], $_SESSION['manage_doctors'], $_SESSION['errors'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel / Reschedule</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Cancel / Reschedule Appointment</h2>
        <p id="msg"><?php echo $success ? htmlspecialchars($success) : ""; ?></p>
        <?php if (!empty($errors)) { ?>
            <div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div>
        <?php } ?>
        <form method="GET" action="../../controllers/receptionistManageAppointmentController.php" novalidate style="margin-bottom:16px;">
            <input type="text" name="search" placeholder="Patient name, phone or appointment ID" value="<?php echo htmlspecialchars($search); ?>" style="width:55%;padding:10px;border:1px solid #ccc;border-radius:4px;">
            <input type="submit" value="Search" style="background:#0033a0;color:#fff;border:none;padding:10px 18px;border-radius:4px;cursor:pointer;margin-left:8px;">
        </form>
        <?php if (!empty($results)) { ?>
            <?php foreach ($results as $a) { $s=$a['status']; ?>
            <div class="form-section" style="margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                    <div>
                        <strong><?php echo htmlspecialchars($a['patient_name']); ?></strong> —
                        <?php echo date('h:i A', strtotime($a['appointment_time'])); ?> with
                        <strong><?php echo htmlspecialchars($a['doctor_name']); ?></strong>
                    </div>
                    <span class="badge-<?php echo $s; ?>"><?php echo ucfirst(str_replace('_',' ',$s)); ?></span>
                </div>
                <?php if ($s != "cancelled" && $s != "completed") { ?>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <form action="../../controllers/receptionistManageAppointmentController.php" method="POST" novalidate>
                        <input type="hidden" name="action" value="cancel">
                        <input type="hidden" name="appointment_id" value="<?php echo $a['id']; ?>">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this appointment?');">Cancel Appointment</button>
                    </form>
                    <button class="btn btn-warning" onclick="openReschedule(<?php echo $a['id']; ?>, <?php echo $a['doctor_id']; ?>)">Reschedule</button>
                </div>
                <div id="reschedule_<?php echo $a['id']; ?>" style="display:none;margin-top:12px;background:#f8f9fa;padding:14px;border-radius:6px;">
                    <form action="../../controllers/receptionistManageAppointmentController.php" method="POST" novalidate>
                        <input type="hidden" name="action" value="reschedule">
                        <input type="hidden" name="appointment_id" value="<?php echo $a['id']; ?>">
                        <input type="hidden" name="doctor_id_for_slots" value="<?php echo $a['doctor_id']; ?>">
                        <label>New Date:</label>
                        <input type="date" name="new_date" id="new_date_<?php echo $a['id']; ?>" min="<?php echo date('Y-m-d'); ?>" onchange="loadRescheduleSlots(<?php echo $a['id']; ?>, <?php echo $a['doctor_id']; ?>)" style="width:auto;">
                        <label style="margin-top:10px;">New Time Slot:</label>
                        <div id="reschedule_slots_<?php echo $a['id']; ?>"><p style="font-size:13px;">Select a date first.</p></div>
                        <input type="hidden" name="new_time" id="new_time_<?php echo $a['id']; ?>">
                        <input type="submit" value="Confirm Reschedule" style="margin-top:10px;">
                    </form>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<?php include "../partials/receptionistRight.php"; ?>
</div>
<?php include "../partials/receptionistFooter.php"; ?>
<script src="../js/receptionistManageAppointment.js"></script>