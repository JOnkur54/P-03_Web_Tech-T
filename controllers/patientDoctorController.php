<?php
session_start();
require_once '../model/patientModel.php';

if (isset($_GET['action']) && $_GET['action'] === 'getSlots') {
    $doctor_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
    $appointment_date = isset($_GET['date']) ? $_GET['date'] : '';
    $slots = [];

    if ($doctor_id && $appointment_date) {
        $slots = getAvailableSlots($conn, $doctor_id, $appointment_date);
    }

    header('Content-Type: application/json');
    echo json_encode(['slots' => $slots]);
    exit();
}

?>