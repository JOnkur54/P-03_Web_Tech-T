<?php

class ModelDoctorAuth {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($email, $password) {
        $sql = "SELECT id, name, password_hash, role FROM users WHERE email = ? AND is_active = 1";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password_hash'])) {
                if ($row['role'] !== 'doctor') {
                    return ['error' => 'Unauthorized access.'];
                }

                // Get doctor_id
                $user_id = $row['id'];
                $doc_sql = "SELECT id FROM doctors WHERE user_id = ?";
                $doc_stmt = mysqli_prepare($this->conn, $doc_sql);
                mysqli_stmt_bind_param($doc_stmt, "i", $user_id);
                mysqli_stmt_execute($doc_stmt);
                $doc_result = mysqli_stmt_get_result($doc_stmt);
                
                if ($doc_row = mysqli_fetch_assoc($doc_result)) {
                    return [
                        'user_id' => $row['id'],
                        'name' => $row['name'],
                        'role' => $row['role'],
                        'doctor_id' => $doc_row['id']
                    ];
                } else {
                    return ['error' => 'Doctor profile not found.'];
                }
            }
        }

        return ['error' => 'Invalid email or password.'];
    }
}
?>