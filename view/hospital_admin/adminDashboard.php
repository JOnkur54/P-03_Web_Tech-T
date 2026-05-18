<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != "admin") {
    header("Location: adminLogin.php");
    exit();
}

if (!isset($_SESSION['dash_today_count'])) {
    header("Location: ../../controllers/adminDashboardController.php");
    exit();
}

$totalTodayAppointments = $_SESSION['dash_today_count'];
$totalPatients          = $_SESSION['dash_total_patients'];
$totalActiveDoctors     = $_SESSION['dash_total_doctors'];
$totalPendingBillings   = $_SESSION['dash_pending_bills'];
$todayAppointments      = $_SESSION['dash_today_appts'];
$recentPatients         = $_SESSION['dash_recent_patients'];
$pendingDoctorApprovals = $_SESSION['dash_pending_doctors'];

unset(
    $_SESSION['dash_today_count'],
    $_SESSION['dash_total_patients'],
    $_SESSION['dash_total_doctors'],
    $_SESSION['dash_pending_bills'],
    $_SESSION['dash_today_appts'],
    $_SESSION['dash_recent_patients'],
    $_SESSION['dash_pending_doctors']
);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/adminDashboard.css">
</head>
<body>

<?php include "../partials/adminHeader.php"; ?>

<div class="layout">

<?php include "../partials/adminLeft.php"; ?>

<div class="main">

    <div style="margin-bottom:20px;">
        <h2 style="font-size:22px;color:#0033a0;font-weight:700;">Dashboard</h2>
        <p style="color:#666;font-size:13px;">
            Welcome back, <strong><?php echo htmlspecialchars(isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin'); ?></strong> &mdash;
            <?php echo date('l, d F Y'); ?>
        </p>
    </div>

    <?php if ($pendingDoctorApprovals > 0) { ?>
    <div class="alert-banner">
        &#9888; There are <strong><?php echo $pendingDoctorApprovals; ?></strong>
        pending doctor registration(s) awaiting approval.
        <a href="../../controllers/adminManageDoctorsController.php">Review now &rarr;</a>
    </div>
    <?php } ?>

    <div class="stat-grid">

        <div class="stat-box appointments">
            <span class="stat-icon">&#128197;</span>
            <span class="stat-value"><?php echo $totalTodayAppointments; ?></span>
            <span class="stat-label">Today's Appointments</span>
        </div>

        <div class="stat-box patients">
            <span class="stat-icon">&#128101;</span>
            <span class="stat-value"><?php echo $totalPatients; ?></span>
            <span class="stat-label">Registered Patients</span>
        </div>

        <div class="stat-box doctors">
            <span class="stat-icon">&#129657;</span>
            <span class="stat-value"><?php echo $totalActiveDoctors; ?></span>
            <span class="stat-label">Active Doctors</span>
        </div>

        <div class="stat-box billing">
            <span class="stat-icon">&#129534;</span>
            <span class="stat-value"><?php echo $totalPendingBillings; ?></span>
            <span class="stat-label">Pending Billings</span>
        </div>

    </div>

    <div class="card">
        <h2>Today's Appointments
            <span style="font-size:13px;font-weight:400;color:#666;margin-left:10px;">
                (<?php echo date('d M Y'); ?>)
            </span>
        </h2>

        <?php if (empty($todayAppointments)) { ?>
            <div class="empty-state">No appointments scheduled for today.</div>
        <?php } else { ?>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
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
                        foreach ($todayAppointments as $appt) {
                            $s   = $appt['status'];
                            $cls = 'badge badge-' . $s;
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars(date('h:i A', strtotime($appt['appointment_time']))); ?></td>
                            <td><?php echo htmlspecialchars($appt['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                            <td><?php echo htmlspecialchars($appt['specialization']); ?></td>
                            <td style="text-transform:capitalize;"><?php echo htmlspecialchars($appt['booked_by']); ?></td>
                            <td>
                                <span class="<?php echo $cls; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $s)); ?>
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top:12px;text-align:right;">
                <a href="../../controllers/adminAllAppointmentsController.php"
                   style="font-size:13px;color:#0033a0;text-decoration:none;font-weight:600;">
                    View all appointments &rarr;
                </a>
            </div>
        <?php } ?>
    </div>

    <div class="card">
        <h2>Recent Patient Registrations</h2>

        <?php if (empty($recentPatients)) { ?>
            <div class="empty-state">No patients registered yet.</div>
        <?php } else { ?>
            <ul class="recent-list">
                <?php foreach ($recentPatients as $p) { ?>
                <li>
                    <span class="r-date"><?php echo htmlspecialchars(date('d M Y', strtotime($p['created_at']))); ?></span>
                    <div class="r-name"><?php echo htmlspecialchars($p['name']); ?></div>
                    <div class="r-email"><?php echo htmlspecialchars($p['email']); ?></div>
                </li>
                <?php } ?>
            </ul>
            <div style="margin-top:12px;text-align:right;">
                <a href="../../controllers/adminManagePatientsController.php"
                   style="font-size:13px;color:#0033a0;text-decoration:none;font-weight:600;">
                    View all patients &rarr;
                </a>
            </div>
        <?php } ?>
    </div>

</div>

<?php include "../partials/adminRight.php"; ?>

</div>

<?php include "../partials/adminFooter.php"; ?>