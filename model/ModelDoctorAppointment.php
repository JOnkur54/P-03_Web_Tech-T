<?php

class ModelDoctorAppointment {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getTodaySchedule($doctor_id) {
        $sql = "SELECT a.id, a.appointment_time, a.reason, a.status, u.name AS patient_name
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE a.doctor_id = ? AND a.appointment_date = CURDATE()
                ORDER BY a.appointment_time ASC";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $appointments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
        return $appointments;
    }

    public function getWeeklySchedule($doctor_id) {
        $sql = "SELECT a.id, a.appointment_date, a.appointment_time, a.reason, a.status, u.name AS patient_name
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE a.doctor_id = ? AND a.appointment_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $appointments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
        return $appointments;
    }

    public function updateStatus($appointment_id, $status) {
        $sql = "UPDATE appointments SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $status, $appointment_id);
        return mysqli_stmt_execute($stmt);
    }

    public function getUpcomingFollowUps($doctor_id) {
        $sql = "SELECT cn.*, u.name AS patient_name
                FROM consultation_notes cn
                JOIN patients p ON cn.patient_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE cn.doctor_id = ? AND cn.follow_up_date >= CURDATE()
                ORDER BY cn.follow_up_date ASC";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $follow_ups = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $follow_ups[] = $row;
        }
        return $follow_ups;
    }
}
?>
