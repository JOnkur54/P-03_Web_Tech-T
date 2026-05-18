<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") { header("Location: adminLogin.php"); exit(); }
if (!isset($_SESSION['all_appointments'])) { header("Location: ../../controllers/adminAllAppointmentsController.php"); exit(); }

$appointments = $_SESSION['all_appointments'];
$doctors      = isset($_SESSION['doctors_list']) ? $_SESSION['doctors_list'] : [];
$filters      = isset($_SESSION['appt_filters']) ? $_SESSION['appt_filters'] : [];
unset($_SESSION['all_appointments'], $_SESSION['doctors_list'], $_SESSION['appt_filters']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Appointments</title>
    <link rel="stylesheet" href="../css/adminManageDoctors.css">
</head>
<body>
<?php include "../partials/adminHeader.php"; ?>

<div class="layout">
<?php include "../partials/adminLeft.php"; ?>

<div class="main">
    <div class="card">
        <h2>All Appointments</h2>

        <form method="GET" action="../../controllers/adminAllAppointmentsController.php" style="margin-bottom:16px;">
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:10px;">
                <select name="doctor_id" style="padding:8px;border:1px solid #ccc;border-radius:4px;">
                    <option value="">All Doctors</option>
                    <?php foreach ($doctors as $doc) { ?>
                        <option value="<?php echo $doc['id']; ?>" <?php if (isset($filters['doctor_id']) && $filters['doctor_id'] == $doc['id']) { echo "selected"; } ?>>
                            <?php echo htmlspecialchars($doc['name']); ?>
                        </option>
                    <?php } ?>
                </select>

                <input type="date" name="date" value="<?php echo htmlspecialchars(isset($filters['date']) ? $filters['date'] : ''); ?>" style="padding:8px;border:1px solid #ccc;border-radius:4px;">

                <select name="status" style="padding:8px;border:1px solid #ccc;border-radius:4px;">
                    <option value="">All Statuses</option>
                    <?php
                    $statuses = ['pending','confirmed','checked_in','completed','cancelled','no_show'];
                    foreach ($statuses as $st) {
                    ?>
                        <option value="<?php echo $st; ?>" <?php if (isset($filters['status']) && $filters['status'] == $st) { echo "selected"; } ?>>
                            <?php echo ucfirst(str_replace('_', ' ', $st)); ?>
                        </option>
                    <?php } ?>
                </select>

                <select name="booked_by" style="padding:8px;border:1px solid #ccc;border-radius:4px;">
                    <option value="">All Sources</option>
                    <option value="patient" <?php if (isset($filters['booked_by']) && $filters['booked_by'] == "patient") { echo "selected"; } ?>>Patient</option>
                    <option value="receptionist" <?php if (isset($filters['booked_by']) && $filters['booked_by'] == "receptionist") { echo "selected"; } ?>>Receptionist</option>
                </select>

                <input type="submit" value="Filter" style="background:#0033a0;color:#fff;border:none;padding:8px 18px;border-radius:4px;cursor:pointer;">
                <a href="../../controllers/adminAllAppointmentsController.php" style="padding:8px 14px;color:#0033a0;text-decoration:none;">Reset</a>
            </div>
        </form>

        <button onclick="window.print()" style="background:#198754;color:#fff;border:none;padding:8px 16px;border-radius:4px;cursor:pointer;margin-bottom:12px;">Print Table</button>

        <?php if (empty($appointments)) { ?>
            <p>No appointments found.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Booked By</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($appointments as $appt) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($appt['appointment_date']); ?></td>
                        <td><?php echo date('h:i A', strtotime($appt['appointment_time'])); ?></td>
                        <td><?php echo htmlspecialchars($appt['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($appt['specialization']); ?></td>
                        <td><?php echo ucfirst($appt['booked_by']); ?></td>
                        <td><span class="badge-<?php echo $appt['status']; ?>" style="padding:3px 8px;border-radius:12px;font-size:11px;"><?php echo ucfirst(str_replace('_', ' ', $appt['status'])); ?></span></td>
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