<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header("Location: ../view/ViewDocLogin.php");
    exit;
}

include '../model/connect.php';
$conn = connect();
include '../model/ModelDoctorAvailability.php';

$doctor_id = $_SESSION['doctor_id'];
$availabilityModel = new ModelDoctorAvailability($conn);

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_availability') {
        $avail_data = $_POST['avail'] ?? [];
        $formatted_data = [];
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        foreach ($days as $day) {
            $formatted_data[$day] = [
                'is_available' => isset($avail_data[$day]['is_available']) ? 1 : 0,
                'start_time' => $avail_data[$day]['start_time'] ?? '09:00:00',
                'end_time' => $avail_data[$day]['end_time'] ?? '17:00:00',
                'slot_duration' => $avail_data[$day]['slot_duration'] ?? 30
            ];
        }

        $result = $availabilityModel->updateAvailability($doctor_id, $formatted_data);
        if ($result) {
            header("Location: ../view/ViewDocAvailability.php?success=1");
        } else {
            header("Location: ../view/ViewDocAvailability.php?error=1");
        }
        exit;
    } elseif ($action === 'add_leave') {
        $leave_date = $_POST['leave_date'] ?? '';
        $reason = $_POST['reason'] ?? '';

        if (empty($leave_date)) {
            header("Location: ../view/ViewDocAvailability.php?error=empty_date");
            exit;
        }

        $result = $availabilityModel->addLeaveDate($doctor_id, $leave_date, $reason);
        if ($result) {
            header("Location: ../view/ViewDocAvailability.php?success=1");
        } else {
            header("Location: ../view/ViewDocAvailability.php?error=1");
        }
        exit;
    }
}

// Handle GET requests (Delete Leave)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'delete_leave') {
        $leave_id = $_GET['id'] ?? 0;
        
        if (!$leave_id) {
            header("Location: ../view/ViewDocAvailability.php?error=invalid_id");
            exit;
        }

        $result = $availabilityModel->deleteLeaveDate($leave_id);
        if ($result) {
            header("Location: ../view/ViewDocAvailability.php?success=1");
        } else {
            header("Location: ../view/ViewDocAvailability.php?error=1");
        }
        exit;
    }
}


?>