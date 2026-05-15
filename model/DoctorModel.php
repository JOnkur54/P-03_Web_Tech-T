<?php

class DoctorModel{

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getAllDoctors(){

        $sql = "
        SELECT doctors.id,
               users.name,
               specializations.name AS specialization,
               doctors.consultation_fee,
               doctors.experience_years
        FROM doctors
        JOIN users ON doctors.user_id = users.id
        JOIN specializations
        ON doctors.specialization_id = specializations.id
        WHERE doctors.is_approved = 1
        ";

        return $this->conn->query($sql);
    }
}
?>