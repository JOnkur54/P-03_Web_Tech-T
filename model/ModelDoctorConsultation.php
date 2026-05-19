<?php

class ModelDoctorConsultation {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAppointmentDetails($appointment_id) {
        $sql = "SELECT a.*, u.name AS patient_name, p.id AS patient_id
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE a.id = ?";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $appointment_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function addConsultationNotes($data) {
        // Start transaction
        mysqli_begin_transaction($this->conn);

        try {
            // Insert notes
            $sql = "INSERT INTO consultation_notes (appointment_id, doctor_id, patient_id, symptoms, diagnosis, prescription, follow_up_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "iiissss", 
                $data['appointment_id'], 
                $data['doctor_id'], 
                $data['patient_id'], 
                $data['symptoms'], 
                $data['diagnosis'], 
                $data['prescription'], 
                $data['follow_up_date']
            );
            mysqli_stmt_execute($stmt);

            // Update appointment status to completed
            $sql_update = "UPDATE appointments SET status = 'completed' WHERE id = ?";
            $stmt_update = mysqli_prepare($this->conn, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "i", $data['appointment_id']);
            mysqli_stmt_execute($stmt_update);

            mysqli_commit($this->conn);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            return false;
        }
    }

    public function getPatientHistory($doctor_id, $patient_id) {
        $sql = "SELECT cn.*, a.appointment_date
                FROM consultation_notes cn
                JOIN appointments a ON cn.appointment_id = a.id
                WHERE cn.doctor_id = ? AND cn.patient_id = ?
                ORDER BY a.appointment_date DESC";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $doctor_id, $patient_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $history = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $history[] = $row;
        }
        return $history;
    }
}
?>
