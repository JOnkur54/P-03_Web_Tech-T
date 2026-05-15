<?php
session_start();

if (!isset($_SESSION['patient_id'])) {

    header("Location: login.php");
    exit();
}

require_once "../../model/connect.php";
require_once "../../model/patientModel.php";

/*
|--------------------------------------------------------------------------
| Get Doctor ID
|--------------------------------------------------------------------------
*/

$doctor_id = 0;

if (isset($_GET['doctor_id'])) {

    $doctor_id =
        (int)$_GET['doctor_id'];
}

/*
|--------------------------------------------------------------------------
| Get Doctor Information
|--------------------------------------------------------------------------
*/

$doctor = getDoctorById(
    $conn,
    $doctor_id
);

if (!$doctor) {

    header("Location: doctors.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Get Availability
|--------------------------------------------------------------------------
*/

$availability = getDoctorAvailability(
    $conn,
    $doctor_id
);
?>

<?php include "../partials/header.php"; ?>

<div class="container">

    <?php include "../partials/left.php"; ?>

    <div class="main">

        <div class="card">

            <h2>

                <?php
                echo htmlspecialchars(
                    $doctor['doctor_name']
                );
                ?>

            </h2>

            <p>

                <strong>Specialization:</strong>

                <?php
                echo htmlspecialchars(
                    $doctor['specialization']
                );
                ?>

            </p>

            <br>

            <p>

                <strong>Experience:</strong>

                <?php
                echo htmlspecialchars(
                    $doctor['experience_years']
                );
                ?>

                years

            </p>

            <br>

            <p>

                <strong>Fee:</strong>

                ৳

                <?php
                echo htmlspecialchars(
                    $doctor['consultation_fee']
                );
                ?>

            </p>

            <br>

            <p>

                <strong>Bio:</strong>

                <?php
                echo nl2br(
                    htmlspecialchars(
                        $doctor['bio']
                    )
                );
                ?>

            </p>

            <br>

            <p>

                <strong>Contact:</strong>

                <?php
                echo htmlspecialchars(
                    $doctor['email']
                );
                ?>

                |

                <?php
                echo htmlspecialchars(
                    $doctor['phone']
                );
                ?>

            </p>

            <br>

            <h3>Availability</h3>

            <?php if (empty($availability)) { ?>

                <p>

                    Doctor has no available
                    schedule listed right now.

                </p>

            <?php } else { ?>

                <ul>

                    <?php foreach ($availability as $slot) { ?>

                        <li>

                            <?php
                            echo htmlspecialchars(
                                $slot['day_of_week']
                            );
                            ?>

                            :

                            <?php
                            echo date(
                                "h:i A",
                                strtotime(
                                    $slot['start_time']
                                )
                            );
                            ?>

                            -

                            <?php
                            echo date(
                                "h:i A",
                                strtotime(
                                    $slot['end_time']
                                )
                            );
                            ?>

                        </li>

                    <?php } ?>

                </ul>

            <?php } ?>

            <br>

            <a
                href="bookAppointment.php?doctor_id=<?php echo (int)$doctor_id; ?>"
                style="
                    color:#0d6efd;
                    text-decoration:none;
                ">

                Book an appointment
                with this doctor

            </a>

        </div>

    </div>

    <?php include "../partials/right.php"; ?>

</div>

<?php include "../partials/footer.php"; ?>