<?php

class ModelDoctorAuth {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($email, $password) {
        $sql  = "SELECT id, name, password_hash, role FROM users WHERE email = ? AND is_active = 1";
        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            return ['error' => 'Database error. Please try again.'];
        }

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {

            $isValid = false;

            // Check if stored hash is a proper bcrypt hash
            if (strlen($row['password_hash']) >= 60 && $row['password_hash'][0] === '$') {
                // Bcrypt hash — use password_verify
                $isValid = password_verify($password, $row['password_hash']);
            } else {
                // Plaintext stored (old record) — direct compare
                $isValid = ($password === $row['password_hash']);
                // Auto-upgrade to bcrypt hash
                if ($isValid) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $upd     = mysqli_prepare($this->conn,
                        "UPDATE users SET password_hash = ? WHERE id = ?");
                    mysqli_stmt_bind_param($upd, "si", $newHash, $row['id']);
                    mysqli_stmt_execute($upd);
                }
            }

            if ($isValid) {
                if ($row['role'] !== 'doctor') {
                    return ['error' => 'Unauthorized access. This portal is for doctors only.'];
                }

                // Get doctor_id
                $user_id  = $row['id'];
                $doc_sql  = "SELECT id FROM doctors WHERE user_id = ?";
                $doc_stmt = mysqli_prepare($this->conn, $doc_sql);
                mysqli_stmt_bind_param($doc_stmt, "i", $user_id);
                mysqli_stmt_execute($doc_stmt);
                $doc_result = mysqli_stmt_get_result($doc_stmt);

                if ($doc_row = mysqli_fetch_assoc($doc_result)) {
                    return [
                        'user_id'   => $row['id'],
                        'name'      => $row['name'],
                        'role'      => $row['role'],
                        'doctor_id' => $doc_row['id']
                    ];
                } else {
                    return ['error' => 'Doctor profile not found. Please contact admin.'];
                }
            }
        }

        return ['error' => 'Invalid email or password.'];
    }
}
?>