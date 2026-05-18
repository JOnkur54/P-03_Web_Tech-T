<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../../controllers/patientUpcomingAppointmentsController.php");
    exit();
}

$appointments = isset($_SESSION['appointments']) ? $_SESSION['appointments'] : [];
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";

unset($_SESSION['appointments'], $_SESSION['errors'], $_SESSION['success']);
?>

<?php include "../partials/header.php"; ?>
<?php include "../partials/left.php"; ?>

<div class="main">
    <div class="card">

        <h2>Upcoming Appointments</h2>

        <?php if (!empty($success)) { ?>
            <div class="success"><?php echo $success; ?></div>
        <?php } ?>

        <?php if (!empty($errors)) { ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <?php if (empty($appointments)) { ?>
            <p>No upcoming appointments scheduled.</p>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Doctor</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment) { ?>
                        <tr>
                            <td><?php echo $appointment['appointment_date']; ?></td>
                            <td><?php echo date("h:i A", strtotime($appointment['appointment_time'])); ?></td>
                            <td><?php echo $appointment['doctor_name']; ?></td>
                            <td>
                                <?php
                                $status = $appointment['status'];
                                if ($status == "pending") {
                                    echo "<span class='pending'>Pending</span>";
                                } elseif ($status == "confirmed") {
                                    echo "<span class='confirmed'>Confirmed</span>";
                                } elseif ($status == "checked_in") {
                                    echo "<span class='checked_in'>Checked In</span>";
                                } else {
                                    echo "<span>" . $status . "</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <a href="../../controllers/patientAppointmentController.php?action=cancel&id=<?php echo $appointment['id']; ?>" class="cancel-btn">Cancel</a>
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

<link rel="stylesheet" href="../css/upcomingAppointments.css">