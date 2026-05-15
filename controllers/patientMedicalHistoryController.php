<?php
session_start();
require_once '../model/patientModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient = getPatientByUserId($conn, $_SESSION['patient_id']);
    $patient_id = $patient ? $patient['id'] : null;

    if ($patient_id && !empty($_POST['note_text'])) {
        $noteText = trim($_POST['note_text']);
        if (addPatientMedicalNote($conn, $patient_id, $noteText)) {
            $_SESSION['success'] = 'Medical note added successfully.';
        } else {
            $_SESSION['errors'][] = 'Unable to add medical note.';
        }
    } else {
        $_SESSION['errors'][] = 'Please enter a note.';
    }
}

header('Location: ../view/hospital appointment booking/medicalHistory.php');
exit();
?>