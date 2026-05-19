<?php

class ModelDoctorReview {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getReviews($doctor_id) {
        $sql = "SELECT r.*, u.name AS patient_name
                FROM doctor_reviews r
                JOIN patients p ON r.patient_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE r.doctor_id = ?
                ORDER BY r.created_at DESC";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
        return $reviews;
    }
}
?>
