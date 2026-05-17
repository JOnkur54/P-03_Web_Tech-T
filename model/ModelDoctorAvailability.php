<?php

class ModelDoctorAvailability {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAvailability($doctor_id) {
        $sql = "SELECT * FROM doctor_availability WHERE doctor_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $availability = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $availability[$row['day_of_week']] = $row;
        }
        return $availability;
    }

    public function updateAvailability($doctor_id, $data) {
        // Data should be an array of days with start_time, end_time, slot_duration
        foreach ($data as $day => $settings) {
            // Check if exists
            $check_sql = "SELECT id FROM doctor_availability WHERE doctor_id = ? AND day_of_week = ?";
            $check_stmt = mysqli_prepare($this->conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "is", $doctor_id, $day);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);

            if (mysqli_num_rows($check_result) > 0) {
                // Update
                $sql = "UPDATE doctor_availability SET start_time = ?, end_time = ?, slot_duration_minutes = ?, is_available = ? WHERE doctor_id = ? AND day_of_week = ?";
                $stmt = mysqli_prepare($this->conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssiiis", $settings['start_time'], $settings['end_time'], $settings['slot_duration'], $settings['is_available'], $doctor_id, $day);
            } else {
                // Insert
                $sql = "INSERT INTO doctor_availability (doctor_id, day_of_week, start_time, end_time, slot_duration_minutes, is_available) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($this->conn, $sql);
                mysqli_stmt_bind_param($stmt, "isssii", $doctor_id, $day, $settings['start_time'], $settings['end_time'], $settings['slot_duration'], $settings['is_available']);
            }
            mysqli_stmt_execute($stmt);
        }
        return true;
    }

    public function getLeaveDates($doctor_id) {
        $sql = "SELECT * FROM leave_dates WHERE doctor_id = ? ORDER BY leave_date ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $leaves = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $leaves[] = $row;
        }
        return $leaves;
    }

    public function addLeaveDate($doctor_id, $date, $reason) {
        $sql = "INSERT INTO leave_dates (doctor_id, leave_date, reason) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $doctor_id, $date, $reason);
        return mysqli_stmt_execute($stmt);
    }

    public function deleteLeaveDate($leave_id) {
        $sql = "DELETE FROM leave_dates WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $leave_id);
        return mysqli_stmt_execute($stmt);
    }
}
?>
