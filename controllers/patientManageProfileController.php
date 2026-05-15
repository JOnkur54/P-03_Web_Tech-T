<?php
session_start();
require_once '../model/patientModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['patient_id'];

    $data = [
        'user_id' => $user_id,
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'phone' => trim($_POST['phone']),
        'dob' => $_POST['dob'],
        'blood_group' => trim($_POST['blood_group']),
        'gender' => trim($_POST['gender']),
        'address' => trim($_POST['address']),
        'emergency_name' => trim($_POST['emergency_name']),
        'emergency_phone' => trim($_POST['emergency_phone'])
    ];

    if (updatePatientProfile($conn, $data)) {
        $_SESSION['success'] = 'Profile updated successfully.';
    } else {
        $_SESSION['errors'][] = 'Unable to update profile. Please try again.';
    }

    header('Location: ../view/hospital appointment booking/manageProfile.php');
    exit();
}
?>