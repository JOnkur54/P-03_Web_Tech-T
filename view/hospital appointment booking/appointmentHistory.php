<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['past_appointments'])) {
    header("Location: ../../controllers/patientAppointmentHistoryShowController.php");
    exit();
}

$appointments = $_SESSION['past_appointments'];

unset($_SESSION['past_appointments']);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History</title>
    <link rel="stylesheet" href="../css/appointmentHistory.css">
</head>
<body>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2>Appointment History</h2>

        <?php if (empty($appointments)) { ?>
            <p>No past appointments are available yet.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Doctor</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment) { ?>
                        <tr>
                            <td><?php echo $appointment['appointment_date']; ?></td>
                            <td><?php echo date("h:i A", strtotime($appointment['appointment_time'])); ?></td>
                            <td><?php echo $appointment['doctor_name']; ?></td>
                            <td><?php echo ucfirst(str_replace('_', ' ', $appointment['status'])); ?></td>
                            <td>
                                <a href="doctorDetails.php?doctor_id=<?php echo $appointment['doctor_id']; ?>" class="view-link">View</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>

    </div>
</div>

<?php include "../partials/right.php"; ?>
<?php include "../partials/footer.php"; ?>

</body>
</html>