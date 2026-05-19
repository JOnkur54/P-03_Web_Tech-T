<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

if (!isset($_SESSION['patient_id'])) {
    header("Location: ../view/hospital_patient/patientLogin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../controllers/patientReviewsShowController.php");
    exit();
}

$conn    = connect();
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);

if (!$patient || !isset($patient['id'])) {
    close($conn);
    $_SESSION['errors'] = ["Patient not found."];
    header("Location: ../controllers/patientReviewsShowController.php");
    exit();
}

$patient_id = (int)$patient['id'];
$action     = isset($_POST['action']) ? trim($_POST['action']) : "submit";

/* ── SUBMIT ──────────────────────────────────────────────────────── */
if ($action == "submit") {

    $appointment_id = isset($_POST['appointment_id']) ? (int)$_POST['appointment_id'] : 0;
    $doctor_id      = isset($_POST['doctor_id'])      ? (int)$_POST['doctor_id']      : 0;
    $rating         = isset($_POST['rating'])         ? (int)$_POST['rating']         : 0;
    $review_text    = isset($_POST['review_text'])    ? trim($_POST['review_text'])    : "";

    if ($rating < 1 || $rating > 5) {
        $_SESSION['errors'] = ["Please select a star rating (1–5)."];
    } elseif ($appointment_id == 0 || $doctor_id == 0) {
        $_SESSION['errors'] = ["Invalid appointment or doctor."];
    } else {
        $data = [
            'appointment_id' => $appointment_id,
            'patient_id'     => $patient_id,
            'doctor_id'      => $doctor_id,
            'rating'         => $rating,
            'review_text'    => $review_text,
        ];
        if (addDoctorReview($conn, $data)) {
            $_SESSION['success'] = "Review submitted successfully. Thank you!";
        } else {
            $_SESSION['errors'] = ["Could not submit review. You may have already reviewed this appointment."];
        }
    }
}

/* ── EDIT ────────────────────────────────────────────────────────── */
if ($action == "edit") {

    $review_id   = isset($_POST['review_id'])   ? (int)$_POST['review_id']          : 0;
    $rating      = isset($_POST['rating'])       ? (int)$_POST['rating']             : 0;
    $review_text = isset($_POST['review_text'])  ? trim($_POST['review_text'])        : "";

    if ($rating < 1 || $rating > 5) {
        $_SESSION['errors'] = ["Please select a star rating."];
    } elseif ($review_id == 0) {
        $_SESSION['errors'] = ["Invalid review."];
    } else {
        if (updateReview($conn, $review_id, $patient_id, ['rating' => $rating, 'review_text' => $review_text])) {
            $_SESSION['success'] = "Review updated successfully.";
        } else {
            $_SESSION['errors'] = ["Could not update review."];
        }
    }
}

/* ── DELETE ──────────────────────────────────────────────────────── */
if ($action == "delete") {

    $review_id = isset($_POST['review_id']) ? (int)$_POST['review_id'] : 0;

    if ($review_id > 0 && deleteReview($conn, $review_id, $patient_id)) {
        $_SESSION['success'] = "Review deleted.";
    } else {
        $_SESSION['errors'] = ["Could not delete review."];
    }
}

/* ── Reload and redirect ─────────────────────────────────────────── */
$_SESSION['reviews']         = getDoctorReviews($conn, $patient_id);
$_SESSION['pending_reviews'] = getAppointmentsPendingReview($conn, $patient_id);
close($conn);

header("Location: ../view/hospital_patient/patientReviews.php");
exit();
?>