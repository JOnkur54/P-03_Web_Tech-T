<?php

/* ── RECEPTIONISTS ─────────────────────────────────────────────── */

function adminGetAllReceptionists($conn)
{
    $sql = "SELECT id, name, email, phone, is_active, created_at
            FROM users WHERE role = 'receptionist'
            ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function adminAddReceptionist($conn, $data)
{
    $name     = mysqli_real_escape_string($conn, $data['name']);
    $email    = mysqli_real_escape_string($conn, $data['email']);
    $phone    = mysqli_real_escape_string($conn, $data['phone']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (name, email, password_hash, phone, role, is_active)
            VALUES ('$name', '$email', '$password', '$phone', 'receptionist', 1)";
    return mysqli_query($conn, $sql);
}

function adminUpdateReceptionist($conn, $user_id, $name, $email, $phone)
{
    $user_id = (int)$user_id;
    $name    = mysqli_real_escape_string($conn, $name);
    $email   = mysqli_real_escape_string($conn, $email);
    $phone   = mysqli_real_escape_string($conn, $phone);
    $sql = "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$user_id AND role='receptionist'";
    return mysqli_query($conn, $sql);
}

function adminToggleReceptionistStatus($conn, $user_id, $status)
{
    $user_id = (int)$user_id;
    $status  = (int)$status;
    $sql = "UPDATE users SET is_active=$status WHERE id=$user_id AND role='receptionist'";
    return mysqli_query($conn, $sql);
}

/* ── PATIENTS ───────────────────────────────────────────────────── */

function adminGetAllPatients($conn, $search = "")
{
    $where = "";
    if ($search != "") {
        $search = mysqli_real_escape_string($conn, $search);
        $where  = "AND (u.name LIKE '%$search%' OR u.email LIKE '%$search%' OR u.phone LIKE '%$search%')";
    }
    $sql = "SELECT u.id, u.name, u.email, u.phone, u.is_active, u.created_at,
                   p.gender, p.blood_group, p.date_of_birth
            FROM users u
            LEFT JOIN patients p ON p.user_id = u.id
            WHERE u.role = 'patient' $where
            ORDER BY u.created_at DESC";
    $result = mysqli_query($conn, $sql);
    $rows = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function adminTogglePatientStatus($conn, $user_id, $status)
{
    $user_id = (int)$user_id;
    $status  = (int)$status;
    $sql = "UPDATE users SET is_active=$status WHERE id=$user_id AND role='patient'";
    return mysqli_query($conn, $sql);
}