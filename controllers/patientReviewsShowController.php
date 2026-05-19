<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital_patient/patientLogin.php");
    exit();
}

$conn    = connect();
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);

$reviews         = [];
$pending_reviews = [];

if ($patient && isset($patient['id'])) {
    $reviews         = getDoctorReviews($conn, $patient['id']);
    $pending_reviews = getAppointmentsPendingReview($conn, $patient['id']);
}

close($conn);

$_SESSION['reviews']         = $reviews;
$_SESSION['pending_reviews'] = $pending_reviews;

header("Location: ../view/hospital_patient/patientReviews.php");
exit();