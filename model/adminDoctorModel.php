<?php

function adminGetAllDoctors($conn)
{
    $sql = "SELECT d.id, d.is_approved, d.consultation_fee, d.experience_years, d.license_number,
                   d.specialization_id, d.bio,
                   u.name, u.email, u.phone, u.is_active,
                   s.name AS specialization
            FROM doctors d
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            ORDER BY d.is_approved ASC, u.name ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function adminGetDoctorById($conn, $doctor_id)
{
    $doctor_id = (int)$doctor_id;
    $sql = "SELECT d.*, u.name, u.email, u.phone, u.is_active, s.name AS specialization
            FROM doctors d
            JOIN users u ON u.id = d.user_id
            JOIN specializations s ON s.id = d.specialization_id
            WHERE d.id = $doctor_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function adminGetAllSpecializations($conn)
{
    $sql = "SELECT id, name FROM specializations ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function adminAddDoctor($conn, $data)
{
    mysqli_begin_transaction($conn);

    $name       = mysqli_real_escape_string($conn, $data['name']);
    $email      = mysqli_real_escape_string($conn, $data['email']);
    $password   = password_hash($data['password'], PASSWORD_DEFAULT);
    $phone      = mysqli_real_escape_string($conn, $data['phone']);

    $sql = "INSERT INTO users (name, email, password_hash, phone, role, is_active)
            VALUES ('$name', '$email', '$password', '$phone', 'doctor', 1)";
    if (!mysqli_query($conn, $sql)) {
        mysqli_rollback($conn);
        return false;
    }

    $user_id          = mysqli_insert_id($conn);
    $spec_id          = (int)$data['specialization_id'];
    $fee              = (float)$data['consultation_fee'];
    $exp              = (int)$data['experience_years'];
    $license          = mysqli_real_escape_string($conn, $data['license_number']);
    $bio              = mysqli_real_escape_string($conn, $data['bio']);

    $sql = "INSERT INTO doctors (user_id, specialization_id, bio, consultation_fee, license_number, experience_years, is_approved)
            VALUES ($user_id, $spec_id, '$bio', $fee, '$license', $exp, 1)";
    if (!mysqli_query($conn, $sql)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return true;
}

function adminUpdateDoctor($conn, $data)
{
    $doctor_id  = (int)$data['doctor_id'];
    $name       = mysqli_real_escape_string($conn, $data['name']);
    $email      = mysqli_real_escape_string($conn, $data['email']);
    $phone      = mysqli_real_escape_string($conn, $data['phone']);
    $spec_id    = (int)$data['specialization_id'];
    $fee        = (float)$data['consultation_fee'];
    $exp        = (int)$data['experience_years'];
    $license    = mysqli_real_escape_string($conn, $data['license_number']);
    $bio        = mysqli_real_escape_string($conn, $data['bio']);

    $doc = adminGetDoctorById($conn, $doctor_id);
    if (!$doc) { return false; }
    $user_id = (int)$doc['user_id'];

    $sql1 = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$user_id";
    $sql2 = "UPDATE doctors SET specialization_id=$spec_id, consultation_fee=$fee,
             experience_years=$exp, license_number='$license', bio='$bio'
             WHERE id=$doctor_id";

    return mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2);
}

function adminApproveDoctorById($conn, $doctor_id)
{
    $doctor_id = (int)$doctor_id;
    $sql = "UPDATE doctors SET is_approved = 1 WHERE id = $doctor_id";
    return mysqli_query($conn, $sql);
}

function adminRejectDoctorById($conn, $doctor_id)
{
    $doctor_id = (int)$doctor_id;
    $doc = adminGetDoctorById($conn, $doctor_id);
    if (!$doc) { return false; }
    $user_id = (int)$doc['user_id'];
    $sql = "UPDATE users SET is_active = 0 WHERE id = $user_id";
    return mysqli_query($conn, $sql);
}

function adminDeactivateDoctorById($conn, $doctor_id)
{
    $doctor_id = (int)$doctor_id;
    $doc = adminGetDoctorById($conn, $doctor_id);
    if (!$doc) { return false; }
    $user_id = (int)$doc['user_id'];
    $sql = "UPDATE users SET is_active = 0 WHERE id = $user_id";
    return mysqli_query($conn, $sql);
}

function adminActivateDoctorById($conn, $doctor_id)
{
    $doctor_id = (int)$doctor_id;
    $doc = adminGetDoctorById($conn, $doctor_id);
    if (!$doc) { return false; }
    $user_id = (int)$doc['user_id'];
    $sql = "UPDATE users SET is_active = 1 WHERE id = $user_id";
    return mysqli_query($conn, $sql);
}