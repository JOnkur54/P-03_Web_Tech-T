<?php

require_once 'connect.php';

function patientEmailExists($email) {
    global $conn;

    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT id FROM users WHERE email = '$email' LIMIT 1";

    $result = mysqli_query($conn, $sql);
    return $result && mysqli_num_rows($result) > 0;
}

function registerPatient($data) {
    global $conn;

    $name = mysqli_real_escape_string($conn, $data['name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $password = mysqli_real_escape_string($conn, $data['password']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $role = mysqli_real_escape_string($conn, $data['role']);
    $profile_pic = mysqli_real_escape_string($conn, $data['profile_pic'] ?? '');

    mysqli_begin_transaction($conn);

    $userSql = "INSERT INTO users (name, email, password_hash, phone, role, profile_pic) VALUES ('$name', '$email', '$password', '$phone', '$role', '$profile_pic')";
    if (!mysqli_query($conn, $userSql)) {
        mysqli_rollback($conn);
        return false;
    }

    $user_id = mysqli_insert_id($conn);

    $dob = mysqli_real_escape_string($conn, $data['dob']);
    $blood_group = mysqli_real_escape_string($conn, $data['blood_group']);
    $gender = mysqli_real_escape_string($conn, $data['gender']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    $emergency_name = mysqli_real_escape_string($conn, $data['emergency_name']);
    $emergency_phone = mysqli_real_escape_string($conn, $data['emergency_phone']);
    $medical_history = mysqli_real_escape_string($conn, $data['medical_history']);

    $patientSql = "INSERT INTO patients (user_id, date_of_birth, blood_group, gender, address, emergency_contact_name, emergency_contact_phone, medical_history_notes) VALUES ($user_id, '$dob', '$blood_group', '$gender', '$address', '$emergency_name', '$emergency_phone', '$medical_history')";
    if (!mysqli_query($conn, $patientSql)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return true;
}

?>