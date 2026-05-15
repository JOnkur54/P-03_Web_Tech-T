<?php
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

require_once "../../model/connect.php";
require_once "../../model/patientModel.php";

$patient = getPatientByUserId($conn, $_SESSION['patient_id']);

$appointments = [];

if ($patient && isset($patient['id'])) {
    $appointments = getUpcomingAppointments($conn, $patient['id']);
}

$errors = [];
$success = "";

if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Appointments</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial, sans-serif;
            background:#f1f3f6;
        }

        .container{
            display:flex;
            min-height:100vh;
        }

        .main{
            width:60%;
            padding:20px;
        }

        .card{
            background:white;
            padding:20px;
            border-radius:5px;
            box-shadow:0 2px 5px rgba(0,0,0,0.1);
        }

        .success{
            background:#d1e7dd;
            color:#0f5132;
            padding:10px;
            margin-bottom:15px;
            border-radius:4px;
        }

        .error{
            background:#f8d7da;
            color:#842029;
            padding:10px;
            margin-bottom:15px;
            border-radius:4px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }

        table th{
            background:#e9ecef;
            padding:12px;
            text-align:left;
            border:1px solid #ccc;
        }

        table td{
            padding:12px;
            border:1px solid #ccc;
        }

        .cancel-btn{
            background:#dc3545;
            color:white;
            padding:8px 12px;
            border:none;
            border-radius:4px;
            cursor:pointer;
        }

        .cancel-btn:hover{
            background:#bb2d3b;
        }

        .status{
            padding:5px 10px;
            border-radius:4px;
            color:white;
            font-size:14px;
        }

        .pending{
            background:orange;
        }

        .confirmed{
            background:green;
        }

        .checked_in{
            background:#0d6efd;
        }

        h2{
            margin-bottom:20px;
        }

    </style>

</head>

<body>

<?php include "../partials/header.php"; ?>

<div class="container">

    <?php include "../partials/left.php"; ?>

    <div class="main">

        <div class="card">

            <h2>Upcoming Appointments</h2>

            <?php if (!empty($success)) { ?>
                <div class="success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php } ?>

            <?php if (!empty($errors)) { ?>
                <div class="error">
                    <ul>
                        <?php foreach ($errors as $error) { ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
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

                                <td>
                                    <?php echo htmlspecialchars($appointment['appointment_date']); ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        date(
                                            "h:i A",
                                            strtotime($appointment['appointment_time'])
                                        )
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($appointment['doctor_name']); ?>
                                </td>

                                <td>

                                    <span class="status <?php echo htmlspecialchars($appointment['status']); ?>">

                                        <?php
                                        echo htmlspecialchars(
                                            ucfirst(
                                                str_replace('_', ' ', $appointment['status'])
                                            )
                                        );
                                        ?>

                                    </span>

                                </td>

                                <td>

                                    <a
                                        href="../../controllers/patientAppointmentController.php?action=cancel&id=<?php echo $appointment['id']; ?>"
                                        class="cancel-btn"
                                        style="text-decoration:none;display:inline-block;">

                                        Cancel

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

</body>

</html>