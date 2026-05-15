<?php
session_start();
require_once '../model/patientModel.php';

$patient = getPatientByUserId($conn, $_SESSION['patient_id']);
$patient_id = $patient ? $patient['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $patient_id) {
    $data = [
        'name' => trim($_POST['name']),
        'dob' => $_POST['dob'],
        'relationship' => trim($_POST['relationship']),
        'blood_group' => trim($_POST['blood_group'])
    ];

    if (addDependent($conn, $patient_id, $data)) {
        $_SESSION['success'] = 'Dependent added successfully.';
    } else {
        $_SESSION['errors'][] = 'Unable to add dependent.';
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) && $patient_id) {
    if (removeDependent($conn, (int)$_GET['id'], $patient_id)) {
        $_SESSION['success'] = 'Dependent removed successfully.';
    } else {
        $_SESSION['errors'][] = 'Unable to delete dependent.';
    }
}

header('Location: ../view/hospital appointment booking/dependents.php');
exit();
?>