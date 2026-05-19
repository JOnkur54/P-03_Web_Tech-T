<?php
session_start();
if (!isset($_SESSION['receptionist_id']) || $_SESSION['role'] != "receptionist") { header("Location: receptionistLogin.php"); exit(); }
if (!isset($_SESSION['search_results'])) { header("Location: ../../controllers/receptionistSearchPatientController.php"); exit(); }
$results      = $_SESSION['search_results'];
$search       = isset($_SESSION['search_query'])  ? $_SESSION['search_query']  : "";
$view_patient = isset($_SESSION['view_patient'])  ? $_SESSION['view_patient']  : null;
$upcoming     = isset($_SESSION['view_upcoming']) ? $_SESSION['view_upcoming'] : [];
$billing      = isset($_SESSION['view_billing'])  ? $_SESSION['view_billing']  : [];
unset($_SESSION['search_results'], $_SESSION['search_query'], $_SESSION['view_patient'], $_SESSION['view_upcoming'], $_SESSION['view_billing']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Patient</title>
    <link rel="stylesheet" href="../css/receptionist.css">
</head>
<body>
<?php include "../partials/receptionistHeader.php"; ?>
<div class="layout">
<?php include "../partials/receptionistLeft.php"; ?>
<div class="main">
    <div class="card">
        <h2>Search Patient</h2>
        <form method="GET" action="../../controllers/receptionistSearchPatientController.php" novalidate>
            <input type="text" name="search" placeholder="Name, phone number or patient ID" value="<?php echo htmlspecialchars($search); ?>" style="width:60%;padding:10px;border:1px solid #ccc;border-radius:4px;">
            <input type="submit" value="Search" style="background:#0033a0;color:#fff;border:none;padding:10px 18px;border-radius:4px;cursor:pointer;margin-left:8px;">
            <a href="../../controllers/receptionistSearchPatientController.php" style="margin-left:10px;color:#0033a0;">Reset</a>
        </form>
    </div>
    <?php if ($search != "" && empty($results)) { ?>
        <div class="card"><p>No patients found for "<?php echo htmlspecialchars($search); ?>".</p></div>
    <?php } ?>
    <?php if (!empty($results)) { ?>
    <div class="card">
        <h2>Search Results</h2>
        <table>
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Gender</th><th>Blood Group</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php $i=1; foreach ($results as $p) { ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo htmlspecialchars($p['email']); ?></td>
                <td><?php echo htmlspecialchars($p['phone']); ?></td>
                <td><?php echo htmlspecialchars($p['gender']); ?></td>
                <td><?php echo htmlspecialchars($p['blood_group']); ?></td>
                <td><?php echo $p['is_active'] == 1 ? '<span class="badge-confirmed">Active</span>' : '<span class="badge-cancelled">Inactive</span>'; ?></td>
                <td>
                    <a href="../../controllers/receptionistSearchPatientController.php?patient_id=<?php echo $p['patient_id']; ?>" class="btn btn-primary" style="text-decoration:none;padding:5px 10px;font-size:12px;">View Profile</a>
                    <a href="../../controllers/receptionistBookAppointmentController.php" class="btn btn-success" style="text-decoration:none;padding:5px 10px;font-size:12px;margin-left:4px;">Book Appt</a>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if ($view_patient) { ?>
    <div class="card">
        <h2>Patient Profile</h2>
        <table>
            <tr><th>Name</th><td><?php echo htmlspecialchars($view_patient['name']); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($view_patient['email']); ?></td></tr>
            <tr><th>Phone</th><td><?php echo htmlspecialchars($view_patient['phone']); ?></td></tr>
            <tr><th>Gender</th><td><?php echo htmlspecialchars($view_patient['gender']); ?></td></tr>
            <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($view_patient['date_of_birth']); ?></td></tr>
            <tr><th>Blood Group</th><td><?php echo htmlspecialchars($view_patient['blood_group']); ?></td></tr>
            <tr><th>Address</th><td><?php echo htmlspecialchars($view_patient['address']); ?></td></tr>
        </table>
    </div>
    <?php if (!empty($upcoming)) { ?>
    <div class="card">
        <h2>Upcoming Appointments</h2>
        <table>
            <thead><tr><th>Date</th><th>Time</th><th>Doctor</th><th>Specialization</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($upcoming as $a) { $s=$a['status']; ?>
            <tr>
                <td><?php echo $a['appointment_date']; ?></td>
                <td><?php echo date('h:i A', strtotime($a['appointment_time'])); ?></td>
                <td><?php echo htmlspecialchars($a['doctor_name']); ?></td>
                <td><?php echo htmlspecialchars($a['specialization']); ?></td>
                <td><span class="badge-<?php echo $s; ?>"><?php echo ucfirst(str_replace('_',' ',$s)); ?></span></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php if (!empty($billing)) { ?>
    <div class="card">
        <h2>Billing Status (Last 5)</h2>
        <table>
            <thead><tr><th>Date</th><th>Doctor</th><th>Amount (&#2547;)</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($billing as $b) { ?>
            <tr>
                <td><?php echo $b['appointment_date']; ?></td>
                <td><?php echo htmlspecialchars($b['doctor_name']); ?></td>
                <td><?php echo number_format($b['amount'],2); ?></td>
                <td><?php echo $b['payment_status'] == 'paid' ? '<span class="badge-paid">Paid</span>' : '<span class="badge-pending">Pending</span>'; ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    <?php } ?>
</div>
<?php include "../partials/receptionistRight.php"; ?>
</div>
<?php include "../partials/receptionistFooter.php"; ?>