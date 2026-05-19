<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    // For AJAX requests, return JSON error
    if (isset($_POST['action']) && $_POST['action'] === 'check_in_ajax') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }
    header("Location: ../view/ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
include '../model/ModelDoctorAppointment.php';

$appointmentModel = new ModelDoctorAppointment($conn);

// Handle AJAX Check-in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'check_in_ajax') {
    $appointment_id = $_POST['id'] ?? 0;
    
    if (!$appointment_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid Appointment ID']);
        exit;
    }

    $result = $appointmentModel->updateStatus($appointment_id, 'checked_in');
    
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}

// Handle GET requests (Confirm, Reject, No-Show)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $appointment_id = $_GET['id'];
    $status = '';

    switch ($action) {
        case 'confirm':
            $status = 'confirmed';
            break;
        case 'reject':
            $status = 'cancelled'; // Or 'rejected' if we had that, but schema says 'cancelled'
            break;
        case 'noshow':
            $status = 'no_show';
            break;
        default:
            header("Location: ../view/ViewDocDashboard.php");
            exit;
    }

    $result = $appointmentModel->updateStatus($appointment_id, $status);
    
    if ($result) {
        header("Location: ../view/ViewDocDashboard.php?success=status_updated");
    } else {
        header("Location: ../view/ViewDocDashboard.php?error=update_failed");
    }
    exit;
}

header("Location: ../view/ViewDocDashboard.php");
exit;
?>
