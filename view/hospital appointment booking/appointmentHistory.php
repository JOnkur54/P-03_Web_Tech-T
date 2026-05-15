<?php
session_start();

if (!isset($_SESSION['patient_id'])) {

    header("Location: login.php");
    exit();
}

require_once "../../model/connect.php";
require_once "../../model/patientModel.php";

$patient = getPatientByUserId(
    $conn,
    $_SESSION['patient_id']
);

$appointments = getPastAppointments(
    $conn,
    $patient['id'] ?? 0
);
?>

<?php include "../partials/header.php"; ?>

<div class="container">

    <?php include "../partials/left.php"; ?>

    <div class="main">

        <div class="card">

            <h2>Appointment History</h2>

            <?php if (empty($appointments)) { ?>

                <p>
                    No past appointments are available yet.
                </p>

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

                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $appointment['appointment_date']
                                    );
                                    ?>

                                </td>

                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        date(
                                            "h:i A",
                                            strtotime(
                                                $appointment['appointment_time']
                                            )
                                        )
                                    );
                                    ?>

                                </td>

                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $appointment['doctor_name']
                                    );
                                    ?>

                                </td>

                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $appointment['status']
                                            )
                                        )
                                    );
                                    ?>

                                </td>

                                <td>

                                    <a
                                        href="doctorDetails.php?doctor_id=<?php echo (int)$appointment['doctor_id']; ?>"
                                        style="color:#0d6efd;text-decoration:none;">

                                        View

                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            <?php } ?>

        </div>

    </div>

    <?php include "../partials/right.php"; ?>

</div>

<?php include "../partials/footer.php"; ?>