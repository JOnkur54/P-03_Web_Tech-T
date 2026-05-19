<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['checkin_results'])) { header("Location: ../../controllers/receptionistCheckInController.php"); exit(); }
$results = $_SESSION['checkin_results'];
$search  = isset($_SESSION['checkin_search']) ? $_SESSION['checkin_search'] : "";
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
$errors  = isset($_SESSION['errors'])  ? $_SESSION['errors']  : [];
unset($_SESSION['checkin_results'], $_SESSION['checkin_search'], $_SESSION['success'], $_SESSION['errors']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check In Patient</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Check In Patient</h2>
        <p id="msg"><?php echo $success ? htmlspecialchars($success) : ""; ?></p>
        <?php if (!empty($errors)) { ?>
            <div class="error"><ul><?php foreach ($errors as $e) { ?><li><?php echo htmlspecialchars($e); ?></li><?php } ?></ul></div>
        <?php } ?>
        <form method="GET" action="../../controllers/receptionistCheckInController.php" novalidate style="margin-bottom:16px;">
            <input type="text" name="search" placeholder="Patient name, phone or appointment ID" value="<?php echo htmlspecialchars($search); ?>" style="width:55%;padding:10px;border:1px solid #ccc;border-radius:4px;">
            <input type="submit" value="Search" style="background:#0033a0;color:#fff;border:none;padding:10px 18px;border-radius:4px;cursor:pointer;margin-left:8px;">
        </form>
        <?php if ($search != "" && empty($results)) { ?>
            <p>No appointments found for today matching "<?php echo htmlspecialchars($search); ?>".</p>
        <?php } ?>
        <?php if (!empty($results)) { ?>
            <table>
                <thead><tr><th>#</th><th>Appt ID</th><th>Time</th><th>Patient</th><th>Phone</th><th>Doctor</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                <?php $i=1; foreach ($results as $a) { $s=$a['status']; ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $a['id']; ?></td>
                    <td><?php echo date('h:i A', strtotime($a['appointment_time'])); ?></td>
                    <td><?php echo htmlspecialchars($a['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($a['patient_phone']); ?></td>
                    <td><?php echo htmlspecialchars($a['doctor_name']); ?></td>
                    <td><span class="badge-<?php echo $s; ?>"><?php echo ucfirst(str_replace('_',' ',$s)); ?></span></td>
                    <td>
                        <?php if ($s == "pending" || $s == "confirmed") { ?>
                            <form action="../../controllers/receptionistCheckInController.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="checkin">
                                <input type="hidden" name="appointment_id" value="<?php echo $a['id']; ?>">
                                <button type="submit" class="btn btn-success">Check In</button>
                            </form>
                        <?php } else { ?>
                            <span style="color:#666;font-size:12px;"><?php echo ucfirst(str_replace('_',' ',$s)); ?></span>
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