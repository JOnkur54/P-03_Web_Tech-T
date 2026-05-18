<?php

function patientEmailExists($conn, $email) {
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
    
    $result = mysqli_query($conn, $sql);
    return $result && mysqli_num_rows($result) > 0;
}

function registerPatient($conn, $data) {
    $name = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $password_hash = mysqli_real_escape_string($conn, $data['password_hash']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $role = 'patient';

    mysqli_begin_transaction($conn);

    $userSql = "INSERT INTO users (name, email, password_hash, phone, role) 
                VALUES ('$name', '$email', '$password_hash', '$phone', '$role')";
    
    if (!mysqli_query($conn, $userSql)) {
        mysqli_rollback($conn);
        return false;
    }

    $user_id = mysqli_insert_id($conn);

    $dob = mysqli_real_escape_string($conn, $data['dob']);
    $blood_group = isset($data['blood_group']) ? mysqli_real_escape_string($conn, $data['blood_group']) : '';
    $gender = isset($data['gender']) ? mysqli_real_escape_string($conn, $data['gender']) : '';
    $address = isset($data['address']) ? mysqli_real_escape_string($conn, $data['address']) : '';
    $emergency_contact = isset($data['emergency_contact']) ? mysqli_real_escape_string($conn, $data['emergency_contact']) : '';

    $patientSql = "INSERT INTO patients (user_id, date_of_birth, blood_group, gender, address, emergency_contact) 
                   VALUES ($user_id, '$dob', '$blood_group', '$gender', '$address', '$emergency_contact')";
    
    if (!mysqli_query($conn, $patientSql)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return true;
}

?>