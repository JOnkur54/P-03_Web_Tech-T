<?php
session_start();

require_once "../model/connect.php";
require_once "../model/patientModel.php";
require_once "../model/close.php";

header('Content-Type: application/json');

if (!isset($_SESSION['patient_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

/*
|--------------------------------------------------------------------------
| GET AVAILABLE SLOTS (AJAX)
|--------------------------------------------------------------------------
*/
if (isset($_GET['action']) && $_GET['action'] == 'getSlots') {
    
    $doctor_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
    $date = isset($_GET['date']) ? $_GET['date'] : "";
    
    if ($doctor_id == 0 || $date == "") {
        echo json_encode(['slots' => []]);
        exit();
    }
    
    $conn = connect();
    $slots = getAvailableSlots($conn, $doctor_id, $date);
    close($conn);
    
    echo json_encode(['slots' => $slots]);
    exit();
}

echo json_encode(['error' => 'Invalid action']);
exit();
?>