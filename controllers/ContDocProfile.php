<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../view/ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
$conn = connect();
include '../model/ModelDoctorProfile.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_SESSION['doctor_id'];
    
    $data = [
        'bio' => $_POST['bio'] ?? '',
        'consultation_fee' => $_POST['consultation_fee'] ?? 0.0,
        'experience_years' => $_POST['experience_years'] ?? 0,
        'license_number' => $_POST['license_number'] ?? '',
        'specialization_id' => $_POST['specialization_id'] ?? 1
    ];

    $profileModel = new ModelDoctorProfile($conn);
    $result = $profileModel->updateProfile($doctor_id, $data);

    if ($result) {
        header("Location: ../view/ViewDocProfile.php?success=1");
    } else {
        header("Location: ../view/ViewDocProfile.php?error=1");
    }
    exit;
} else {
    header("Location: ../view/ViewDocProfile.php");
    exit;
}
?>