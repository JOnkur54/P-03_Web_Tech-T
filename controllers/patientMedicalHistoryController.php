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
    header("Location: ../view/hospital_patient/patientMedicalHistory.php");
    exit();
}

$conn    = connect();
$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$patient_id = isset($patient['id']) ? $patient['id'] : null;

if (!$patient_id) {
    close($conn);
    $_SESSION['errors'] = ["Patient not found."];
    header("Location: ../controllers/patientMedicalHistoryShowController.php");
    exit();
}

$note_text = isset($_POST['note_text']) ? trim($_POST['note_text']) : "";

if ($note_text == "") {
    close($conn);
    $_SESSION['errors'] = ["Please enter a note."];
    header("Location: ../controllers/patientMedicalHistoryShowController.php");
    exit();
}

if (addPatientMedicalNote($conn, $patient_id, $note_text)) {
    $_SESSION['success'] = "Medical note added successfully.";
} else {
    $_SESSION['errors'] = ["Unable to add note."];
}

$notes = getPatientMedicalNotes($conn, $patient_id);
close($conn);

$_SESSION['medical_notes'] = $notes;

header("Location: ../view/hospital_patient/patientMedicalHistory.php");
exit();