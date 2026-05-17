<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../view/ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
include '../model/ModelDoctorConsultation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_SESSION['doctor_id'];
    
    $data = [
        'appointment_id' => $_POST['appointment_id'] ?? 0,
        'patient_id' => $_POST['patient_id'] ?? 0,
        'doctor_id' => $doctor_id,
        'symptoms' => $_POST['symptoms'] ?? '',
        'diagnosis' => $_POST['diagnosis'] ?? '',
        'prescription' => $_POST['prescription'] ?? '',
        'follow_up_date' => $_POST['follow_up_date'] ?: null
    ];

    if (!$data['appointment_id'] || !$data['patient_id']) {
        header("Location: ../view/ViewDocDashboard.php?error=invalid_data");
        exit;
    }

    $consultationModel = new ModelDoctorConsultation($conn);
    $result = $consultationModel->addConsultationNotes($data);

    if ($result) {
        header("Location: ../view/ViewDocDashboard.php?success=consultation_completed");
    } else {
        header("Location: ../view/ViewDocConsultation.php?appointment_id=" . $data['appointment_id'] . "&error=save_failed");
    }
    exit;
} else {
    header("Location: ../view/ViewDocDashboard.php");
    exit;
}
?>
