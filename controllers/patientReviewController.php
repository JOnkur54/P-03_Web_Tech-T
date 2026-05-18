<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital appointment booking/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../view/hospital appointment booking/reviews.php");
    exit();
}

$conn = connect();
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$patient_id = isset($patient['id']) ? $patient['id'] : null;

if (!$patient_id) {
    close($conn);
    $_SESSION['errors'] = ["Patient not found."];
    header("Location: ../view/hospital appointment booking/reviews.php");
    exit();
}

$data = [
    'appointment_id' => isset($_POST['appointment_id']) ? (int)$_POST['appointment_id'] : 0,
    'patient_id' => $patient_id,
    'doctor_id' => isset($_POST['doctor_id']) ? (int)$_POST['doctor_id'] : 0,
    'rating' => isset($_POST['rating']) ? (int)$_POST['rating'] : 0,
    'review_text' => isset($_POST['review_text']) ? trim($_POST['review_text']) : ""
];

if ($data['appointment_id'] == 0 || $data['doctor_id'] == 0 || $data['rating'] == 0) {
    $_SESSION['errors'] = ["Please fill in all required fields."];
} else {
    if (addDoctorReview($conn, $data)) {
        $_SESSION['success'] = "Review submitted successfully.";
    } else {
        $_SESSION['errors'] = ["Unable to submit review."];
    }
}

$reviews = getDoctorReviews($conn, $patient_id);
$pending_reviews = getAppointmentsPendingReview($conn, $patient_id);

close($conn);

$_SESSION['reviews'] = $reviews;
$_SESSION['pending_reviews'] = $pending_reviews;

header("Location: ../view/hospital appointment booking/reviews.php");
exit();
?>