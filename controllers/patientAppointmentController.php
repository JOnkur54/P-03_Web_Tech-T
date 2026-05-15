<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";

/*
|--------------------------------------------------------------------------
| Cancel Appointment
|--------------------------------------------------------------------------
*/

if (
    isset($_GET['action']) &&
    $_GET['action'] == "cancel"
) {

    if (!isset($_SESSION['patient_id'])) {

        header(
            "Location: ../view/hospital appointment booking/login.php"
        );

        exit();
    }

    $appointment_id =
        (int)$_GET['id'];

    /*
    |--------------------------------------------------------------------------
    | Get Patient
    |--------------------------------------------------------------------------
    */

    $patient = getPatientByUserId(
        $conn,
        $_SESSION['patient_id']
    );

    if (!$patient) {

        $_SESSION['errors'] = [
            "Patient not found."
        ];

        header(
            "Location: ../view/hospital appointment booking/upcomingAppointments.php"
        );

        exit();
    }

    $patient_id = $patient['id'];

    /*
    |--------------------------------------------------------------------------
    | Cancel Appointment
    |--------------------------------------------------------------------------
    */

    $status = cancelAppointment(
        $conn,
        $appointment_id,
        $patient_id
    );

    if ($status) {

        $_SESSION['success'] =
            "Appointment cancelled successfully.";

    } else {

        $_SESSION['errors'] = [
            "Failed to cancel appointment."
        ];
    }

    header(
        "Location: ../view/hospital appointment booking/upcomingAppointments.php"
    );

    exit();
}