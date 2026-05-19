<?php

class ModelDoctorProfile {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getProfile($doctor_id) {
        $sql = "SELECT d.*, u.name, u.email, u.phone, s.name AS specialization_name
                FROM doctors d
                JOIN users u ON d.user_id = u.id
                JOIN specializations s ON d.specialization_id = s.id
                WHERE d.id = ?";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function updateProfile($doctor_id, $data) {
        $sql = "UPDATE doctors SET 
                bio = ?, 
                consultation_fee = ?, 
                experience_years = ?, 
                license_number = ?, 
                specialization_id = ?
                WHERE id = ?";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sdissi", 
            $data['bio'], 
            $data['consultation_fee'], 
            $data['experience_years'], 
            $data['license_number'], 
            $data['specialization_id'],
            $doctor_id
        );
        return mysqli_stmt_execute($stmt);
    }

    public function getSpecializations() {
        $sql = "SELECT id, name FROM specializations";
        $result = mysqli_query($this->conn, $sql);
        $specializations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $specializations[] = $row;
        }
        return $specializations;
    }
}
?>
