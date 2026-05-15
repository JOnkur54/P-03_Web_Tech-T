<?php
session_start();
require_once '../model/patientModel.php';

if (!isset($_SESSION['patient_id'])) {
    header('Location: ../view/hospital appointment booking/login.php');
    exit();
}

$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$patient_id = $patient ? $patient['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $patient_id) {
    $data = [
        'appointment_id' => (int)$_POST['appointment_id'],
        'patient_id' => $patient_id,
        'doctor_id' => (int)$_POST['doctor_id'],
        'rating' => (int)$_POST['rating'],
        'review_text' => trim($_POST['review_text'])
    ];

    if (addDoctorReview($conn, $data)) {
        $_SESSION['success'] = 'Review submitted successfully.';
    } else {
        $_SESSION['errors'][] = 'Unable to submit review.';
    }
}

header('Location: ../view/hospital appointment booking/reviews.php');
exit();
?>